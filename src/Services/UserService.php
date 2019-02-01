<?php

namespace WebAppId\User\Services;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\DB;
use WebAppId\User\Models\User;
use WebAppId\User\Repositories\ActivationRepository;
use WebAppId\User\Repositories\UserRepository;
use WebAppId\User\Repositories\UserRoleRepository;
use WebAppId\User\Response\AddUserResponse;
use WebAppId\User\Response\ChangePasswordResponse;
use WebAppId\User\Response\UserSearchResponse;
use WebAppId\User\Services\Params\ChangePasswordParam;
use WebAppId\User\Services\Params\UserParam;
use WebAppId\User\Services\Params\UserRoleParam;
use WebAppId\User\Services\Params\UserSearchParam;

/**
 * Class UserService
 * @package App\Http\Services
 */
class UserService
{
    private $container;
    
    /**
     * UserService constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
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
            for ($i = 0; $i < count($request->getRoles()); $i++) {
                $userRoleParam->setUserId($resultUser->id);
                $userRoleParam->setRoleId($request->getRoles()[$i]);
                
                $resultUserRole = $this->container->call([$userRoleRepository, 'addUserRole'], ['request' => $userRoleParam]);
            }
            if ($resultUserRole == null) {
                DB::rollback();
                $addUserResponse->setStatus(false);
                $addUserResponse->setMessage('add user role failed');
                return null;
            } else {
                $resultActivation = $this->container->call([$activationRepository, 'addActivation'], ['userId' => $resultUser->id]);
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
}