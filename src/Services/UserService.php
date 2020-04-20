<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\User\Repositories\Requests\UserRepositoryRequest;
use WebAppId\User\Repositories\Requests\UserRoleRepositoryRequest;
use WebAppId\User\Services\Requests\UserServiceRequest;
use WebAppId\User\Services\Responses\UserServiceResponse;
use WebAppId\User\Services\Responses\UserServiceResponseList;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use WebAppId\DDD\Services\BaseService;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\User\Repositories\ActivationRepository;
use WebAppId\User\Repositories\UserRepository;
use WebAppId\User\Repositories\UserRoleRepository;
use WebAppId\User\Response\AddUserResponse;
use WebAppId\User\Response\ChangePasswordResponse;
use WebAppId\User\Response\UserSearchResponse;
use WebAppId\User\Response\ResetPasswordResponse;
use WebAppId\User\Services\Contracts\UserServiceContract;
use WebAppId\User\Services\Params\ChangePasswordParam;
use WebAppId\User\Services\Params\UserParam;
use WebAppId\User\Services\Params\UserRoleParam;
use WebAppId\User\Services\Params\UserSearchParam;
use WebAppId\User\Models\User;

/**
 * Class UserService
 * @package WebAppId\User\Http\Services
 */
class UserService extends BaseService implements UserServiceContract
{
    /**
     * @inheritDoc
     * @throws BindingResolutionException
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
            return null;
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

    private function storeUserRoles(array $userRoleList, int $userId, UserRoleRepository $userRoleRepository): ?array
    {
        $roles = [];
        foreach ($userRoleList as $userRole) {
            $userRoleRepositoryRequest = $this->container->make(UserRoleRepositoryRequest::class);
            $userRoleRepositoryRequest->role_id = $userRole;
            $userRoleRepositoryRequest->user_id = $userId;

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
                return null;
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
    public function get(UserRepository $userRepository, UserServiceResponseList $userServiceResponseList, int $length = 12): UserServiceResponseList
    {
        $result = $this->container->call([$userRepository, 'get']);

        if (count($result) > 0) {
            $userServiceResponseList->status = true;
            $userServiceResponseList->message = 'Data Found';
            $userServiceResponseList->user = $result;
            $userServiceResponseList->countAll = $this->container->call([$userRepository, 'getCount']);
        } else {
            $userServiceResponseList->status = false;
            $userServiceResponseList->message = 'Data Not Found';
        }

        return $userServiceResponseList;
    }

    /**
     * @inheritDoc
     */
    public function getCount(UserRepository $userRepository): int
    {
        return $this->container->call([$userRepository, 'getCount']);
    }

    /**
     * @inheritDoc
     */
    public function getWhere(string $q, UserRepository $userRepository, UserServiceResponseList $userServiceResponseList, int $length = 12): UserServiceResponseList
    {
        $result = $this->container->call([$userRepository, 'getWhere'], ['q' => $q]);
        if (count($result) > 0) {
            $userServiceResponseList->status = true;
            $userServiceResponseList->message = 'Data Found';
            $userServiceResponseList->userList = $result;
            $userServiceResponseList->countAll = $this->container->call([$userRepository, 'getCount']);
            $userServiceResponseList->countWhere = $this->container->call([$userRepository, 'getWhereCount'], ['q' => $q]);
        } else {
            $userServiceResponseList->status = false;
            $userServiceResponseList->message = 'Data Not Found';
        }
        return $userServiceResponseList;
    }

    /**
     * @inheritDoc
     */
    public function getWhereCount(string $q, UserRepository $userRepository): int
    {
        return $this->container->call([$userRepository, 'getWhereCount'], ['q' => $q]);
    }

    /**
     * @param UserParam $request
     * @param UserRepository $userRepository
     * @param UserRoleRepository $userRoleRepository
     * @param UserRoleParam $userRoleParam
     * @param AddUserResponse $addUserResponse
     * @param ActivationRepository $activationRepository
     * @return AddUserResponse
     */
    public function addUser(UserParam $request,
                            UserRepository $userRepository,
                            UserRoleRepository $userRoleRepository,
                            UserRoleParam $userRoleParam,
                            AddUserResponse $addUserResponse,
                            ActivationRepository $activationRepository): AddUserResponse
    {
        DB::beginTransaction();
        $resultUser = $this->container->call([$userRepository, 'addUser'], ['request' => $request]);
        if ($resultUser == null) {
            $addUserResponse->setStatus(false);
            $addUserResponse->setMessage('add user failed');
            DB::rollback();
            return $addUserResponse;
        } else {
            $resultUserRole = null;
            $resultUserRole = $this->addUserRoles($resultUser->id, $request, $userRoleParam, $userRoleRepository);
            if ($resultUserRole == null) {
                DB::rollback();
                $addUserResponse->setStatus(false);
                $addUserResponse->setMessage('add user role failed');
                return null;
            } else {
                $resultActivation = $this->container->call([$activationRepository, 'store'], ['userId' => $resultUser->id]);
                if ($resultActivation == null) {
                    DB::rollback();
                    $addUserResponse->setStatus(false);
                    $addUserResponse->setMessage('Add Activation Failed');
                } else {
                    $addUserResponse->setStatus(true);
                    $addUserResponse->setMessage('Add User Success');
                    $addUserResponse->setActivation($resultActivation->key);
                    $addUserResponse->setUser($resultUser);
                    $addUserResponse->setRoles($resultUser->roles);
                    DB::commit();
                }

                return $addUserResponse;
            }
        }
    }

    /**
     * @param string $userId
     * @param UserParam $request
     * @param UserRoleParam $userRoleParam
     * @param UserRoleRepository $userRoleRepository
     * @return mixed|null
     */
    private function addUserRoles(string $userId,
                                  UserParam $request,
                                  UserRoleParam $userRoleParam,
                                  UserRoleRepository $userRoleRepository)
    {
        $resultUserRole = null;
        for ($i = 0; $i < count($request->getRoles()); $i++) {
            $userRoleParam->setUserId($userId);
            $userRoleParam->setRoleId($request->getRoles()[$i]);

            $resultUserRole = $this->container->call([$userRoleRepository, 'addUserRole'], ['request' => $userRoleParam]);
        }
        return $resultUserRole;
    }

    /**
     * @param ChangePasswordParam $changePasswordParam
     * @param UserRepository $userRepository
     * @param ChangePasswordResponse $changePasswordResponse
     * @param bool $force
     * @return ChangePasswordResponse
     */
    public function changePassword(ChangePasswordParam $changePasswordParam,
                                   UserRepository $userRepository,
                                   ChangePasswordResponse $changePasswordResponse,
                                   $force = false): ChangePasswordResponse
    {

        $userResult = $this->container->call([$userRepository, 'getUserByEmail'], ['email' => $changePasswordParam->getEmail()]);
        if ($userResult != null) {
            if ($changePasswordParam->getPassword() !== $changePasswordParam->getRetypePassword() && !$force) {
                $changePasswordResponse->setStatus(false);
                $changePasswordResponse->setMessage("New password and retype password not match");
            } elseif (!password_verify($changePasswordParam->getOldPassword(), $userResult->password) && !$force) {
                $changePasswordResponse->setStatus(false);
                $changePasswordResponse->setMessage("Old password not match");
            } else {
                $changePasswordResult = $this->container->call([$userRepository, 'setUpdatePassword'], ['email' => $changePasswordParam->getEmail(), 'password' => $changePasswordParam->getPassword()]);
                if ($changePasswordResult == null) {
                    $changePasswordResponse->setStatus(false);
                    $changePasswordResponse->setMessage("Update Password Failed, please contact your admin");
                } else {
                    $changePasswordResponse->setStatus(true);
                    $changePasswordResponse->setMessage("Update Password Success");
                }
            }
        } else {
            $changePasswordResponse->setStatus(false);
            $changePasswordResponse->setMessage("User not found");
        }

        return $changePasswordResponse;
    }

    /**
     * @param UserSearchParam $userSearchParam
     * @param UserRepository $userRepository
     * @param UserSearchResponse $userSearchResponse
     * @return UserSearchResponse|null
     */
    public function showUserList(UserSearchParam $userSearchParam,
                                 UserRepository $userRepository,
                                 UserSearchResponse $userSearchResponse): ?UserSearchResponse
    {
        $recordTotal = $this->container->call([$userRepository, 'getCountAllUser']);
        $userSearchResponse->setRecordsTotal($recordTotal);
        if ($recordTotal == 0) {
            $userSearchResponse->setStatus(false);
            $userSearchResponse->setMessage('No data found');
        } else {
            $userSearchResponse->setStatus(true);
            $userSearchResponse->setMessage('Data found');
        }

        $recordFiltered = $this->container->call([$userRepository, 'getUserSearchCount'], ['userSearchParam' => $userSearchParam]);
        $userSearchResponse->setRecordsFiltered($recordFiltered);

        $data = $this->container->call([$userRepository, 'getUserSearch'], ['userSearchParam' => $userSearchParam]);
        $userSearchResponse->setData($data);

        return $userSearchResponse;
    }

    /**
     * @param string $email
     * @param UserRepository $userRepository
     * @param UserSearchResponse $userSearchResponse
     * @return UserSearchResponse|null
     */
    public function getUserByEmail(string $email,
                                   UserRepository $userRepository,
                                   UserSearchResponse $userSearchResponse): ?UserSearchResponse
    {
        $result = $this->container->call([$userRepository, 'getUserByEmail'], ['email' => $email]);

        if ($result == null) {
            $userSearchResponse->setStatus(false);
            $userSearchResponse->setMessage('Invalid User');
        } else {
            $userSearchResponse->setStatus(true);
            $userSearchResponse->setMessage('Get User By Email success');
            $userSearchResponse->setData($result);
        }
        return $userSearchResponse;
    }

    /**
     * @param string $email
     * @param int $status
     * @param UserRepository $userRepository
     * @return User
     */
    public function updateUserStatus(string $email, int $status, UserRepository $userRepository): ?User
    {
        return $this->container->call([$userRepository, 'setUpdateStatusUser'], ['email' => $email, 'status' => $status]);
    }

    /**
     * @param string $email
     * @param string $name
     * @param UserRepository $userRepository
     * @return User|null
     */
    public function updateUserName(string $email, string $name, UserRepository $userRepository): ?User
    {
        return $this->container->call([$userRepository, 'setUpdateName'], ['email' => $email, 'name' => $name]);
    }

    /**
     * @param string $email
     * @param UserRepository $userRepository
     * @return bool|null
     */
    public function deleteUserByEmail(string $email, UserRepository $userRepository): ?bool
    {
        return $this->container->call([$userRepository, 'deleteUserByEmail'], ['email' => $email]);
    }

    /**
     * @param UserParam $userParam
     * @param UserRepository $userRepository
     * @param UserRoleParam $userRoleParam
     * @param UserRoleRepository $userRoleRepository
     * @return User|null
     */
    public function updateUser(UserParam $userParam,
                               UserRepository $userRepository,
                               UserRoleParam $userRoleParam,
                               UserRoleRepository $userRoleRepository): ?User
    {
        DB::beginTransaction();

        $user = $this->container->call([$userRepository, 'setUpdateName'], ['email' => $userParam->getEmail(), 'name' => $userParam->getName()]);

        $userStatus = $this->container->call([$userRepository, 'setUpdateStatusUser'], ['email' => $userParam->getEmail(), 'status' => $userParam->getStatusId()]);

        $deleteRoleByUserId = $this->container->call([$userRoleRepository, 'deleteUserRoleByUserId'], ['user_id' => $user->id]);

        $resultUserRole = $this->addUserRoles($user->id, $userParam, $userRoleParam, $userRoleRepository);

        $userUpdated = $this->container->call([$userRepository, 'getUserByEmail'], ['email' => $userParam->getEmail()]);

        if ($user == null || $resultUserRole == null || $userStatus == null || !$deleteRoleByUserId || $userUpdated == null) {
            DB::rollBack();
            return null;
        } else {
            DB::commit();
            return $userUpdated;
        }
    }

    /**
     * @param array $credential
     * @param UserRepository $userRepository
     * @param ResetPasswordResponse $resetPasswordResponse
     * @return ResetPasswordResponse
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
     * @param string $token
     * @param ChangePasswordResponse $changePasswordResponse
     * @return ChangePasswordResponse
     */
    public function getEmailByResetToken(string $token, ChangePasswordResponse $changePasswordResponse): ChangePasswordResponse
    {
        $email = Cache::pull($token);
        if ($email != null) {
            $changePasswordResponse->email = $email;
            $changePasswordResponse->setStatus(true);
        } else {
            $changePasswordResponse->setStatus(false);
        }
        return $changePasswordResponse;
    }
}
