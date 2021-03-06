<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\User\Models\Role;
use WebAppId\User\Repositories\Requests\RoleRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 17:14:01
 * Time: 2020/04/18
 * Class RoleRepositoryContract
 * @package WebAppId\User\Repositories\Contracts
 */
interface RoleRepositoryContract
{
    /**
     * @param RoleRepositoryRequest $dummyRepositoryClassRequest
     * @param Role $role
     * @return Role|null
     */
    public function store(RoleRepositoryRequest $dummyRepositoryClassRequest, Role $role): ?Role;

    /**
     * @param int $id
     * @param RoleRepositoryRequest $dummyRepositoryClassRequest
     * @param Role $role
     * @return Role|null
     */
    public function update(int $id, RoleRepositoryRequest $dummyRepositoryClassRequest, Role $role): ?Role;

    /**
     * @param int $id
     * @param Role $role
     * @return Role|null
     */
    public function getById(int $id, Role $role): ?Role;

    /**
     * @param int $id
     * @param Role $role
     * @return bool
     */
    public function delete(int $id, Role $role): bool;

    /**
     * @param Role $role
     * @param int $length
     * @param string|null $q
     * @return LengthAwarePaginator
     */
    public function get(Role $role, int $length = 12, string $q = null): LengthAwarePaginator;

    /**
     * @param Role $role
     * @param string|null $q
     * @return int
     */
    public function getCount(Role $role, string $q = null): int;


    /**
     * @param string $name
     * @param Role $role
     * @return Role|null
     */
    public function getByName(string $name, Role $role): ?Role;
}
