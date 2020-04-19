<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Repositories\Contracts;

use WebAppId\User\Models\Permission;
use WebAppId\User\Repositories\Requests\PermissionRepositoryRequest;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22:26:58
 * Time: 2020/04/18
 * Class PermissionRepositoryContract
 * @package WebAppId\User\Repositories\Contracts
 */
interface PermissionRepositoryContract
{
    /**
     * @param PermissionRepositoryRequest $dummyRepositoryClassRequest
     * @param Permission $permission
     * @return Permission|null
     */
    public function store(PermissionRepositoryRequest $dummyRepositoryClassRequest, Permission $permission): ?Permission;

    /**
     * @param int $id
     * @param PermissionRepositoryRequest $dummyRepositoryClassRequest
     * @param Permission $permission
     * @return Permission|null
     */
    public function update(int $id, PermissionRepositoryRequest $dummyRepositoryClassRequest, Permission $permission): ?Permission;

    /**
     * @param int $id
     * @param Permission $permission
     * @return Permission|null
     */
    public function getById(int $id, Permission $permission): ?Permission;

    /**
     * @param int $id
     * @param Permission $permission
     * @return bool
     */
    public function delete(int $id, Permission $permission): bool;

    /**
     * @param Permission $permission
     * @param int $length
     * @return LengthAwarePaginator
     */
    public function get(Permission $permission, int $length = 12): LengthAwarePaginator;

    /**
     * @param Permission $permission
     * @return int
     */
    public function getCount(Permission $permission): int;

    /**
     * @param string $q
     * @param Permission $permission
     * @param int $length
     * @return LengthAwarePaginator
     */
    public function getWhere(string $q, Permission $permission, int $length = 12): LengthAwarePaginator;

    /**
     * @param string $q
     * @param Permission $permission
     * @param int $length
     * @return int
     */
    public function getWhereCount(string $q, Permission $permission, int $length = 12): int;

    /**
     * @param string $name
     * @param Permission $permission
     * @return Permission|null
     */
    public function getByName(string $name, Permission $permission): ?Permission;
}
