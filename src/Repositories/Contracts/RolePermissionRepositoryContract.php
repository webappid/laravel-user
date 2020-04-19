<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Repositories\Contracts;

use WebAppId\User\Models\RolePermission;
use WebAppId\User\Repositories\Requests\RolePermissionRepositoryRequest;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 10:46:08
 * Time: 2020/04/19
 * Class RolePermissionRepositoryContract
 * @package App\Repositories\Contracts
 */
interface RolePermissionRepositoryContract
{
    /**
     * @param RolePermissionRepositoryRequest $dummyRepositoryClassRequest
     * @param RolePermission $rolePermission
     * @return RolePermission|null
     */
    public function store(RolePermissionRepositoryRequest $dummyRepositoryClassRequest, RolePermission $rolePermission): ?RolePermission;

    /**
     * @param int $id
     * @param RolePermissionRepositoryRequest $dummyRepositoryClassRequest
     * @param RolePermission $rolePermission
     * @return RolePermission|null
     */
    public function update(int $id, RolePermissionRepositoryRequest $dummyRepositoryClassRequest, RolePermission $rolePermission): ?RolePermission;

    /**
     * @param int $id
     * @param RolePermission $rolePermission
     * @return RolePermission|null
     */
    public function getById(int $id, RolePermission $rolePermission): ?RolePermission;

    /**
     * @param int $id
     * @param RolePermission $rolePermission
     * @return bool
     */
    public function delete(int $id, RolePermission $rolePermission): bool;

    /**
     * @param RolePermission $rolePermission
     * @param int $length
     * @return LengthAwarePaginator
     */
    public function get(RolePermission $rolePermission, int $length = 12): LengthAwarePaginator;

    /**
     * @param RolePermission $rolePermission
     * @return int
     */
    public function getCount(RolePermission $rolePermission): int;
}
