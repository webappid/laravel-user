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
     * @param int $id
     * @param RoleParam $request
     * @param Role $role
     * @return Role|null
     */
    public function updateRole(int $id, RoleParam $request, Role $role): ?Role
    {
        $result = $this->getRoleById($id, $role);
        if ($result != null) {
            try {
                $result->name = $request->getName();
                $result->description = $request->getDescription();
                $result->save();
                return $result;
            } catch (QueryException $e) {
                report($e);
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * @param int $id
     * @param Role $role
     * @return bool
     * @throws \Exception
     */
    public function deleteRole(int $id, Role $role): bool
    {
        $result = $this->getRoleById($id, $role);
        if ($result != null) {
            try {
                $result->delete();
                return true;
            } catch (QueryException $e) {
                report($e);
                return false;
            }
        } else {
            return false;
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