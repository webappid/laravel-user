<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\User\Models\Permission;
use WebAppId\User\Repositories\Requests\PermissionRepositoryRequest;

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
     * @param string|null $q
     * @return LengthAwarePaginator
     */
    public function get(Permission $permission, int $length = 12, string $q = null): LengthAwarePaginator;

    /**
     * @param Permission $permission
     * @param string|null $q
     * @return int
     */
    public function getCount(Permission $permission, string $q = null): int;

    /**
     * @param string $name
     * @param Permission $permission
     * @return Permission|null
     */
    public function getByName(string $name, Permission $permission): ?Permission;
}
