<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services\Contracts;

use Illuminate\Foundation\Application;
use WebAppId\User\Models\User;
use WebAppId\User\Repositories\ActivationRepository;
use WebAppId\User\Repositories\Requests\UserRepositoryRequest;
use WebAppId\User\Repositories\Requests\UserRoleRepositoryRequest;
use WebAppId\User\Repositories\UserRepository;
use WebAppId\User\Repositories\UserRoleRepository;
use WebAppId\User\Services\Requests\RoleServiceRequest;
use WebAppId\User\Services\Requests\UserServiceRequest;
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
     * @param string $q
     * @param UserRepository $userRepository
     * @param UserServiceResponseList $userServiceResponseList
     * @param int $length
     * @return UserServiceResponseList
     */
    public function getWhere(string $q, UserRepository $userRepository, UserServiceResponseList $userServiceResponseList, int $length = 12): UserServiceResponseList;

    /**
     * @param string $q
     * @param UserRepository $userRepository
     * @return int
     */
    public function getWhereCount(string $q, UserRepository $userRepository): int;

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
}
