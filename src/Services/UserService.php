<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\User\Models\User;
use WebAppId\User\Repositories\Requests\UserRepositoryRequest;
use WebAppId\User\Repositories\Requests\UserRoleRepositoryRequest;
use WebAppId\User\Services\Requests\ChangePasswordRequest;
use WebAppId\User\Services\Requests\UserServiceRequest;
use WebAppId\User\Services\Responses\ChangePasswordResponse;
use WebAppId\User\Services\Responses\ResetPasswordResponse;
use WebAppId\User\Services\Responses\UserServiceResponse;
use WebAppId\User\Services\Responses\UserServiceResponseList;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use WebAppId\DDD\Services\BaseService;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\User\Repositories\ActivationRepository;
use WebAppId\User\Repositories\UserRepository;
use WebAppId\User\Repositories\UserRoleRepository;
use WebAppId\User\Services\Contracts\UserServiceContract;

/**
 * Class UserService
 * @package WebAppId\User\Http\Services
 */
class UserService extends BaseService implements UserServiceContract
{
    /**
     * @inheritDoc
     */
    public function store(
        UserServiceRequest $userServiceRequest,
        UserRepositoryRequest $userRepositoryRequest,
        UserRepository $userRepository,
        array $userRoleList,
        UserRoleRepositoryRequest $userRoleRepositoryRequest,
        UserRoleRepository $userRoleRepository,
        ActivationRepository $activationRepository,
        UserServiceResponse $userServiceResponse): UserServiceResponse
    {
        $userRepositoryRequest = Lazy::copy($userServiceRequest, $userRepositoryRequest);

        DB::beginTransaction();
        $result = $this->container->call([$userRepository, 'store'], ['userRepositoryRequest' => $userRepositoryRequest]);

        $roles = $this->storeUserRoles($userRoleList, $result->id, $userRoleRepository);

        if ($roles == null) {
            $userServiceResponse->status = false;
            $userServiceResponse->message = 'Store User Role Failed';
            return $userServiceResponse;
        }

        $resultActivation = $this->container->call([$activationRepository, 'store'], ['userId' => $result->id]);

        if ($resultActivation == null) {
            DB::rollback();
            $userServiceResponse->status = false;
            $userServiceResponse->message = 'User Activation Failed';
        }

        if ($result != null) {
            DB::commit();
            $userServiceResponse->status = true;
            $userServiceResponse->message = 'Store Data Success';
            $userServiceResponse->user = $result;
            $userServiceResponse->activationKey = $resultActivation->key;
            $userServiceResponse->roleList = $roles;
        } else {
            Db::rollBack();
            $userServiceResponse->status = false;
            $userServiceResponse->message = 'Store Data Failed';
        }

        return $userServiceResponse;
    }

    /**
     * @param array $userRoleList
     * @param int $userId
     * @param UserRoleRepository $userRoleRepository
     * @return array|null
     */
    private function storeUserRoles(array $userRoleList, int $userId, UserRoleRepository $userRoleRepository): ?array
    {
        $roles = [];
        foreach ($userRoleList as $userRole) {
            try {
                $userRoleRepositoryRequest = $this->container->make(UserRoleRepositoryRequest::class);
                $userRoleRepositoryRequest->role_id = $userRole;
                $userRoleRepositoryRequest->user_id = $userId;
            } catch (BindingResolutionException $e) {
                report($e);
            }


            $userRoleResult = $this->container->call([$userRoleRepository, 'store'], compact('userRoleRepositoryRequest'));

            if ($userRoleResult == null) {
                Db::rollBack();
                return null;
            }
            $roles[] = $userRoleResult;
        }
        return $roles;
    }

    /**
     * @inheritDoc
     */
    public function update(int $id,
                           UserServiceRequest $userServiceRequest,
                           UserRepositoryRequest $userRepositoryRequest,
                           UserRepository $userRepository,
                           array $userRoleList,
                           UserRoleRepositoryRequest $userRoleRepositoryRequest,
                           UserRoleRepository $userRoleRepository,
                           UserServiceResponse $userServiceResponse): UserServiceResponse
    {
        $userRepositoryRequest = Lazy::copy($userServiceRequest, $userRepositoryRequest);

        DB::beginTransaction();
        $result = $this->container->call([$userRepository, 'update'], compact('id', 'userRepositoryRequest'));

        $removeUserRole = $this->container->call([$userRoleRepository, 'deleteByUserId'], ['user_id' => $id]);

        $roles = [];

        if ($removeUserRole) {
            $roles = $this->storeUserRoles($userRoleList, $result->id, $userRoleRepository);
            if ($roles == null) {
                $userServiceResponse->status = false;
                $userServiceResponse->message = 'Store User Role Failed';
                return $userServiceResponse;
            }
        }

        if ($result != null) {
            DB::commit();
            $userServiceResponse->status = true;
            $userServiceResponse->message = 'Update Data Success';
            $userServiceResponse->user = $result;
            $userServiceResponse->roleList = $roles;
        } else {
            DB::rollBack();
            $userServiceResponse->status = false;
            $userServiceResponse->message = 'Update Data Failed';
        }

        return $userServiceResponse;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, UserRepository $userRepository, UserServiceResponse $userServiceResponse): UserServiceResponse
    {
        $result = $this->container->call([$userRepository, 'getById'], ['id' => $id]);

        if ($result != null) {
            $userServiceResponse->status = true;
            $userServiceResponse->message = 'Data Found';
            $userServiceResponse->user = $result;
        } else {
            $userServiceResponse->status = false;
            $userServiceResponse->message = 'Data Not Found';
        }

        return $userServiceResponse;
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, UserRepository $userRepository): bool
    {
        return $this->container->call([$userRepository, 'delete'], ['id' => $id]);
    }

    /**
     * @inheritDoc
     */
    public function get(UserRepository $userRepository, UserServiceResponseList $userServiceResponseList, int $length = 12, string $q = null): UserServiceResponseList
    {
        $result = $this->container->call([$userRepository, 'get'], ['q' => $q]);

        if (count($result) > 0) {
            $userServiceResponseList->status = true;
            $userServiceResponseList->message = 'Data Found';
            $userServiceResponseList->userList = $result;
            $userServiceResponseList->count = $this->container->call([$userRepository, 'getCount']);
            $userServiceResponseList->countFiltered = $this->container->call([$userRepository, 'getCount'], ['q' => $q]);
        } else {
            $userServiceResponseList->status = false;
            $userServiceResponseList->message = 'Data Not Found';
        }

        return $userServiceResponseList;
    }

    /**
     * @inheritDoc
     */
    public function getCount(UserRepository $userRepository, string $q = null): int
    {
        return $this->container->call([$userRepository, 'getCount'], ['q' => $q]);
    }

    /**
     * @inheritDoc
     */
    public function changePassword(ChangePasswordRequest $changePasswordRequest,
                                   UserRepository $userRepository,
                                   ChangePasswordResponse $changePasswordResponse,
                                   $force = false): ChangePasswordResponse
    {

        $userResult = $this->container->call([$userRepository, 'getByEmail'], ['email' => $changePasswordRequest->email]);
        if ($userResult != null) {
            if ($changePasswordRequest->password !== $changePasswordRequest->retypePassword && !$force) {
                $changePasswordResponse->status = false;
                $changePasswordResponse->message = "New password and retype password not match";
            } elseif (!password_verify($changePasswordRequest->oldPassword, $userResult->password) && !$force) {
                $changePasswordResponse->status = false;
                $changePasswordResponse->message = "Old password not match";
            } else {
                $changePasswordResult = $this->container->call([$userRepository, 'setUpdatePassword'], ['email' => $changePasswordRequest->email, 'password' => $changePasswordRequest->password]);
                if ($changePasswordResult == null) {
                    $changePasswordResponse->status = false;
                    $changePasswordResponse->message = "Update Password Failed, please contact your admin";
                } else {
                    $changePasswordResponse->status = true;
                    $changePasswordResponse->message = "Update Password Success";
                }
            }
        } else {
            $changePasswordResponse->status = false;
            $changePasswordResponse->message = "User not found";
        }

        return $changePasswordResponse;
    }

    /**
     * @inheritDoc
     */
    public function updateUserStatus(string $email, int $status, UserRepository $userRepository): ?User
    {
        return $this->container->call([$userRepository, 'setUpdateStatusUser'], ['email' => $email, 'status' => $status]);
    }

    /**
     * @inheritDoc
     */
    public function updateUserName(string $email, string $name, UserRepository $userRepository): ?User
    {
        return $this->container->call([$userRepository, 'setUpdateName'], ['email' => $email, 'name' => $name]);
    }

    /**
     * @inheritDoc
     */
    public function sendForgotPasswordLink(array $credential, UserRepository $userRepository, ResetPasswordResponse $resetPasswordResponse): ResetPasswordResponse
    {
        $token = $this->container->call([$userRepository, 'setResetPasswordTokenByEmail'], ['email' => $credential['email']]);
        if ($token != null) {
            $resetPasswordResponse->status = true;
            $resetPasswordResponse->message = "token created";
            $resetPasswordResponse->token = $token;
        } else {
            $resetPasswordResponse->status = false;
            $resetPasswordResponse->message = "generate token failed";
        }

        return $resetPasswordResponse;
    }

    /**
     * @inheritDoc
     */
    public function getEmailByResetToken(string $token, ChangePasswordResponse $changePasswordResponse): ChangePasswordResponse
    {
        $email = Cache::pull($token);
        if ($email != null) {
            $changePasswordResponse->email = $email;
            $changePasswordResponse->status = true;
        } else {
            $changePasswordResponse->status = false;
        }
        return $changePasswordResponse;
    }

    /**
     * @inheritDoc
     */
    public function getByEmail(string $email, UserRepository $userRepository, UserServiceResponse $userServiceResponse): ?UserServiceResponse
    {
        $result = $this->container->call([$userRepository, 'getByEmail'], compact('email'));
        if ($result != null) {
            $userServiceResponse->status = true;
            $userServiceResponse->message = "Data Found";
            $userServiceResponse->user = $result;
        } else {
            $userServiceResponse->status = false;
            $userServiceResponse->message = "Data Not Found";
        }

        return $userServiceResponse;
    }

    /**
     * @inheritDoc
     */
    public function setUpdateStatusUser(string $email, int $status, UserRepository $userRepository, UserServiceResponse $userServiceResponse): ?UserServiceResponse
    {
        $result = $this->container->call([$userRepository, 'setUpdateStatusUser'], compact('email', 'status'));
        if (!$result != null) {
            $userServiceResponse->status = true;
            $userServiceResponse->message = "Update Status Success";
            $userServiceResponse->user = $result;
        } else {
            $userServiceResponse->status = false;
            $userServiceResponse->message = "Update Status Failed";
        }
        return $userServiceResponse;
    }

    /**
     * @inheritDoc
     */
    public function setUpdateName(string $email, string $name, UserRepository $userRepository, UserServiceResponse $userServiceResponse): ?UserServiceResponse
    {
        $result = $this->container->call([$userRepository, 'setUpdateName'], compact('email', 'name'));
        if ($result != null) {
            $userServiceResponse->status = true;
            $userServiceResponse->message = "Update Name Success";
            $userServiceResponse->user = $result;
        } else {
            $userServiceResponse->status = false;
            $userServiceResponse->message = "Update Name Failed";
        }
        return $userServiceResponse;
    }

    /**
     * @inheritDoc
     */
    public function deleteByEmail(string $email, UserRepository $userRepository): bool
    {
        return $this->container->call([$userRepository, 'deleteByEmail'], compact('email'));
    }

    /**
     * @inheritDoc
     */
    public function setResetPasswordTokenByEmail(string $email, UserRepository $userRepository, UserServiceResponse $userServiceResponse): UserServiceResponse
    {
        $result = $this->container->call([$userRepository, 'setResetPasswordTokenByEmail'], compact('email'));
        if ($result != null) {
            $userServiceResponse->status = true;
            $userServiceResponse->message = "Reset Password Token By Email Success";
            $userServiceResponse->user = $result;
        } else {
            $userServiceResponse->status = false;
            $userServiceResponse->message = "Reset Password Token By Email Failed";
        }
        return $userServiceResponse;
    }

    /**
     * @inheritDoc
     */
    public function updateRememberToken(int $userId, UserRepository $userRepository, UserServiceResponse $userServiceResponse, bool $revoke = false): UserServiceResponse
    {
        $result = $this->container->call([$userRepository,'updateRememberToken'], compact('userId', 'revoke'));
        if($result!=null){
            unset($result->id);
            unset($result->updated_at);
            unset($result->created_at);
            unset($result->email_verified_at);
            unset($result->remember_token);
            unset($result->password);
            unset($result->status_id);
            $userServiceResponse->status = true;
            $userServiceResponse->user = $result;
            $userServiceResponse->message = 'Update Token Success';
        }else{
            $userServiceResponse->status = false;
            $userServiceResponse->message = 'Update Token Failed';
        }
        return $userServiceResponse;
    }
}
