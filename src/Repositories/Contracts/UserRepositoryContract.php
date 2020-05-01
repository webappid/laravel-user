<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Repositories\Contracts;

use Illuminate\Foundation\Application;
use WebAppId\User\Models\User;
use WebAppId\User\Repositories\Requests\UserRepositoryRequest;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22:56:15
 * Time: 2020/04/18
 * Class UserRepositoryContract
 * @package WebAppId\User\Repositories\Contracts
 */
interface UserRepositoryContract
{
    /**
     * @param UserRepositoryRequest $dummyRepositoryClassRequest
     * @param User $user
     * @return User|null
     */
    public function store(UserRepositoryRequest $dummyRepositoryClassRequest, User $user): ?User;

    /**
     * @param int $id
     * @param UserRepositoryRequest $dummyRepositoryClassRequest
     * @param User $user
     * @return User|null
     */
    public function update(int $id, UserRepositoryRequest $dummyRepositoryClassRequest, User $user): ?User;

    /**
     * @param int $id
     * @param User $user
     * @return User|null
     */
    public function getById(int $id, User $user): ?User;

    /**
     * @param int $id
     * @param User $user
     * @return bool
     */
    public function delete(int $id, User $user): bool;

    /**
     * @param User $user
     * @param int $length
     * @return LengthAwarePaginator
     */
    public function get(User $user, int $length = 12): LengthAwarePaginator;

    /**
     * @param User $user
     * @return int
     */
    public function getCount(User $user): int;

    /**
     * @param string $email
     * @param User $user
     * @return User|null
     */
    public function getByEmail(string $email, User $user): ?User;

    /**
     * @param string $email
     * @param string $password
     * @param User $user
     * @return User|null
     */
    public function setUpdatePassword(string $email, string $password, User $user): ?User;

    /**
     * @param string $email
     * @param int $status
     * @param User $user
     * @return User|null
     */
    public function setUpdateStatusUser(string $email, int $status, User $user): ?User;

    /**
     * @param string $email
     * @param string $name
     * @param User $user
     * @return User|null
     */
    public function setUpdateName(string $email, string $name, User $user): ?User;

    /**
     * @param string $email
     * @param User $user
     * @return bool
     */
    public function deleteByEmail(string $email, User $user): bool;

    /**
     * @param string $email
     * @param Application $application
     * @param User $user
     * @return string
     */
    public function setResetPasswordTokenByEmail(string $email, Application $application, User $user): ?string;
}
