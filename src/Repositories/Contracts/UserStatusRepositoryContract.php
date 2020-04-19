<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Repositories\Contracts;

use WebAppId\User\Models\UserStatus;
use WebAppId\User\Repositories\Requests\UserStatusRepositoryRequest;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 21:27:23
 * Time: 2020/04/18
 * Class UserStatusRepositoryContract
 * @package App\Repositories\Contracts
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
     * @return LengthAwarePaginator
     */
    public function get(UserStatus $userStatus, int $length = 12): LengthAwarePaginator;

    /**
     * @param UserStatus $userStatus
     * @return int
     */
    public function getCount(UserStatus $userStatus): int;

    /**
     * @param string $q
     * @param UserStatus $userStatus
     * @param int $length
     * @return LengthAwarePaginator
     */
    public function getWhere(string $q, UserStatus $userStatus, int $length = 12): LengthAwarePaginator;

    /**
     * @param string $q
     * @param UserStatus $userStatus
     * @param int $length
     * @return int
     */
    public function getWhereCount(string $q, UserStatus $userStatus, int $length = 12): int;
}
