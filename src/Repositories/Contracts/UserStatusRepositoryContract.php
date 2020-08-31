<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\User\Models\UserStatus;
use WebAppId\User\Repositories\Requests\UserStatusRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 21:27:23
 * Time: 2020/04/18
 * Class UserStatusRepositoryContract
 * @package WebAppId\User\Repositories\Contracts
 */
interface UserStatusRepositoryContract
{
    /**
     * @param UserStatusRepositoryRequest $dummyRepositoryClassRequest
     * @param UserStatus $userStatus
     * @return UserStatus|null
     */
    public function store(UserStatusRepositoryRequest $dummyRepositoryClassRequest, UserStatus $userStatus): ?UserStatus;

    /**
     * @param int $id
     * @param UserStatusRepositoryRequest $dummyRepositoryClassRequest
     * @param UserStatus $userStatus
     * @return UserStatus|null
     */
    public function update(int $id, UserStatusRepositoryRequest $dummyRepositoryClassRequest, UserStatus $userStatus): ?UserStatus;

    /**
     * @param int $id
     * @param UserStatus $userStatus
     * @return UserStatus|null
     */
    public function getById(int $id, UserStatus $userStatus): ?UserStatus;

    /**
     * @param int $id
     * @param UserStatus $userStatus
     * @return bool
     */
    public function delete(int $id, UserStatus $userStatus): bool;

    /**
     * @param UserStatus $userStatus
     * @param int $length
     * @param string|null $q
     * @return LengthAwarePaginator
     */
    public function get(UserStatus $userStatus, int $length = 12, string $q = null): LengthAwarePaginator;

    /**
     * @param UserStatus $userStatus
     * @param string|null $q
     * @return int
     */
    public function getCount(UserStatus $userStatus, string $q = null): int;

    /**
     * @param string $name
     * @param UserStatus $userStatus
     * @return UserStatus|null
     */
    public function getByName(string $name, UserStatus $userStatus): ?UserStatus;
}
