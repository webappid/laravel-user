<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Repositories\Contracts;

use WebAppId\User\Models\UserRole;
use WebAppId\User\Repositories\Requests\UserRoleRepositoryRequest;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 00:50:26
 * Time: 2020/04/19
 * Class UserRoleRepositoryContract
 * @package WebAppId\User\Repositories\Contracts
 */
interface UserRoleRepositoryContract
{
    /**
     * @param UserRoleRepositoryRequest $dummyRepositoryClassRequest
     * @param UserRole $userRole
     * @return UserRole|null
     */
    public function store(UserRoleRepositoryRequest $dummyRepositoryClassRequest, UserRole $userRole): ?UserRole;

    /**
     * @param int $id
     * @param UserRoleRepositoryRequest $dummyRepositoryClassRequest
     * @param UserRole $userRole
     * @return UserRole|null
     */
    public function update(int $id, UserRoleRepositoryRequest $dummyRepositoryClassRequest, UserRole $userRole): ?UserRole;

    /**
     * @param int $id
     * @param UserRole $userRole
     * @return UserRole|null
     */
    public function getById(int $id, UserRole $userRole): ?UserRole;

    /**
     * @param int $id
     * @param UserRole $userRole
     * @return bool
     */
    public function delete(int $id, UserRole $userRole): bool;

    /**
     * @param UserRole $userRole
     * @param int $length
     * @return LengthAwarePaginator
     */
    public function get(UserRole $userRole, int $length = 12): LengthAwarePaginator;

    /**
     * @param UserRole $userRole
     * @return int
     */
    public function getCount(UserRole $userRole): int;

    /**
     * @param $user_id
     * @param UserRole $userRole
     * @return bool
     */
    public function deleteByUserId(int $user_id, UserRole $userRole): bool;
}
