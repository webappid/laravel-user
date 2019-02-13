<?php
/**
 * Created by PhpStorm.
 * Users: dyangalih
 * Date: 05/11/18
 * Time: 00.38
 */

namespace WebAppId\User\Repositories;

use WebAppId\User\Models\Role;
use Illuminate\Database\QueryException;
use WebAppId\User\Services\Params\RoleParam;

class RoleRepository
{
    /**
     * @param Role $role
     * @param RoleParam $request
     * @return Role|null
     */
    public function addRole(Role $role, RoleParam $request): ?Role
    {
        try {
            $role->name = $request->getName();
            $role->description = $request->getDescription();
            $role->save();
            return $role;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }
    
    /**
     * @param string $name
     * @param Role $role
     * @return Role|null
     */
    public function getRoleByName(string $name, Role $role): ?Role
    {
        return $role->where('name', $name)->first();
    }
    
    /**
     * @param Role $role
     * @return object|null
     */
    public function getAllRole(Role $role): ?object
    {
        return $role->get();
    }
    
    /**
     * @param int $id
     * @param Role $role
     * @return Role|null
     */
    public function getRoleById(int $id, Role $role): ?Role
    {
        return $role->find($id);
    }
}