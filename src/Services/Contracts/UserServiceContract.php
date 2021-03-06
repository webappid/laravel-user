<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services\Contracts;

use WebAppId\User\Models\User;
use WebAppId\User\Repositories\ActivationRepository;
use WebAppId\User\Repositories\Requests\UserRepositoryRequest;
use WebAppId\User\Repositories\Requests\UserRoleRepositoryRequest;
use WebAppId\User\Repositories\UserRepository;
use WebAppId\User\Repositories\UserRoleRepository;
use WebAppId\User\Services\Requests\ChangePasswordRequest;
use WebAppId\User\Services\Requests\UserServiceRequest;
use WebAppId\User\Services\Responses\ChangePasswordResponse;
use WebAppId\User\Services\Responses\ResetPasswordResponse;
use WebAppId\User\Services\Responses\UserServiceResponse;
use WebAppId\User\Services\Responses\UserServiceResponseList;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 07:58:10
 * Time: 2020/04/20
 * Class UserServiceContract
 * @package WebAppId\User\Services\Contracts
 */
interface UserServiceContract
{
    /**
     * @param UserServiceRequest $userServiceRequest
     * @param UserRepositoryRequest $userRepositoryRequest
     * @param UserRepository $userRepository
     * @param array $userRoleList
     * @param UserRoleRepositoryRequest $userRoleRepositoryRequest
     * @param UserRoleRepository $userRoleRepository
     * @param ActivationRepository $activationRepository
     * @param UserServiceResponse $userServiceResponse
     * @return UserServiceResponse
     */
    public function store(
        UserServiceRequest $userServiceRequest,
        UserRepositoryRequest $userRepositoryRequest,
        UserRepository $userRepository,
        array $userRoleList,
        UserRoleRepositoryRequest $userRoleRepositoryRequest,
        UserRoleRepository $userRoleRepository,
        ActivationRepository $activationRepository,
        UserServiceResponse $userServiceResponse): UserServiceResponse;

    /**
     * @param int $id
     * @param UserServiceRequest $userServiceRequest
     * @param UserRepositoryRequest $userRepositoryRequest
     * @param UserRepository $userRepository
     * @param array $userRoleList
     * @param UserRoleRepositoryRequest $userRoleRepositoryRequest
     * @param UserRoleRepository $userRoleRepository
     * @param UserServiceResponse $userServiceResponse
     * @return UserServiceResponse
     */
    public function update(int $id,
                           UserServiceRequest $userServiceRequest,
                           UserRepositoryRequest $userRepositoryRequest,
                           UserRepository $userRepository,
                           array $userRoleList,
                           UserRoleRepositoryRequest $userRoleRepositoryRequest,
                           UserRoleRepository $userRoleRepository,
                           UserServiceResponse $userServiceResponse): UserServiceResponse;

    /**
     * @param int $id
     * @param UserRepository $userRepository
     * @param UserServiceResponse $userServiceResponse
     * @return UserServiceResponse
     */
    public function getById(int $id, UserRepository $userRepository, UserServiceResponse $userServiceResponse): UserServiceResponse;

    /**
     * @param int $id
     * @param UserRepository $userRepository
     * @return bool
     */
    public function delete(int $id, UserRepository $userRepository): bool;

    /**
     * @param UserRepository $userRepository
     * @param int $length
     * @param UserServiceResponseList $userServiceResponseList
     * @return UserServiceResponseList
     */
    public function get(UserRepository $userRepository, UserServiceResponseList $userServiceResponseList, int $length = 12): UserServiceResponseList;

    /**
     * @param UserRepository $userRepository
     * @return int
     */
    public function getCount(UserRepository $userRepository): int;

    /**
     * @param string $email
     * @param UserRepository $userRepository
     * @param UserServiceResponse $userServiceResponse
     * @return User|null
     */
    public function getByEmail(string $email, UserRepository $userRepository, UserServiceResponse $userServiceResponse): ?UserServiceResponse;

    /**
     * @param string $email
     * @param int $status
     * @param UserRepository $userRepository
     * @param UserServiceResponse $userServiceResponse
     * @return User|null
     */
    public function setUpdateStatusUser(string $email, int $status, UserRepository $userRepository, UserServiceResponse $userServiceResponse): ?UserServiceResponse;

    /**
     * @param string $email
     * @param string $name
     * @param UserRepository $userRepository
     * @param UserServiceResponse $userServiceResponse
     * @return User|null
     */
    public function setUpdateName(string $email, string $name, UserRepository $userRepository, UserServiceResponse $userServiceResponse): ?UserServiceResponse;

    /**
     * @param string $email
     * @param UserRepository $userRepository
     * @return bool
     */
    public function deleteByEmail(string $email, UserRepository $userRepository): bool;

    /**
     * @param string $email
     * @param UserRepository $userRepository
     * @param UserServiceResponse $userServiceResponse
     * @return UserServiceResponse
     */
    public function setResetPasswordTokenByEmail(string $email, UserRepository $userRepository, UserServiceResponse $userServiceResponse): UserServiceResponse;

    /**
     * @param string $token
     * @param ChangePasswordResponse $changePasswordResponse
     * @return ChangePasswordResponse
     */
    public function getEmailByResetToken(string $token, ChangePasswordResponse $changePasswordResponse): ChangePasswordResponse;

    /**
     * @param array $credential
     * @param UserRepository $userRepository
     * @param ResetPasswordResponse $resetPasswordResponse
     * @return ResetPasswordResponse
     */
    public function sendForgotPasswordLink(array $credential, UserRepository $userRepository, ResetPasswordResponse $resetPasswordResponse): ResetPasswordResponse;

    /**
     * @param string $email
     * @param string $name
     * @param UserRepository $userRepository
     * @return User|null
     */
    public function updateUserName(string $email, string $name, UserRepository $userRepository): ?User;

    /**
     * @param string $email
     * @param int $status
     * @param UserRepository $userRepository
     * @return User|null
     */
    public function updateUserStatus(string $email, int $status, UserRepository $userRepository): ?User;

    /**
     * @param ChangePasswordRequest $changePasswordRequest
     * @param UserRepository $userRepository
     * @param ChangePasswordResponse $changePasswordResponse
     * @param bool $force
     * @return ChangePasswordResponse
     */
    public function changePassword(ChangePasswordRequest $changePasswordRequest,
                                   UserRepository $userRepository,
                                   ChangePasswordResponse $changePasswordResponse,
                                   $force = false): ChangePasswordResponse;

    /**
     * @param int $userId
     * @param UserRepository $userRepository
     * @param UserServiceResponse $userServiceResponse
     * @param bool $revoke
     * @return mixed
     */
    public function updateRememberToken(int $userId,
                                        UserRepository $userRepository,
                                        UserServiceResponse $userServiceResponse,
                                        bool $revoke = false): UserServiceResponse;

    /**
     * @param string $email
     * @param UserRepository $userRepository
     * @param UserServiceResponse $userServiceResponse
     * @return mixed
     */
    public function getLoginToken(string $email, UserRepository $userRepository, UserServiceResponse $userServiceResponse): UserServiceResponse;

    /**
     * @param string $token
     * @param UserServiceResponse $userServiceResponse
     * @param UserRepository $userRepository
     * @return mixed
     */
    public function getUserByLoginToken(string $token, UserServiceResponse $userServiceResponse, UserRepository $userRepository): UserServiceResponse;
}
