<?php
/**
 * Created by PhpStorm.
 * User: Fadlika_N
 * Date: 3/6/2019
 * Time: 1:01 PM
 */

namespace WebAppId\User\Repositories;

use WebAppId\User\Models\RolePermission;
use WebAppId\User\Services\Params\RolePermissionParam;
use Illuminate\Database\QueryException;

/**
 * Class RolePermissionRepository
 * @package App\Http\Repositories\Contract
 */
class RolePermissionRepository
{
    /**
     * @param RolePermissionParam $rolePermissionParam
     * @param RolePermission $rolePermission
     * @return RolePermission|null
     */
    public function add(RolePermissionParam $rolePermissionParam, RolePermission $rolePermission): ?RolePermission
    {
        try {
            $rolePermission->role_id = $rolePermissionParam->getRoleId();
            $rolePermission->permission_id = $rolePermissionParam->getPermissionId();
            $rolePermission->save();
            return $rolePermission;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }

    /**
     * @param int $id
     * @param RolePermissionParam $rolePermissionParam
     * @param RolePermission $rolePermission
     * @return RolePermission|null
     */
    public function update(int $id, RolePermissionParam $rolePermissionParam, RolePermission $rolePermission): ?RolePermission
    {
        $result = $this->getById($id, $rolePermission);
        if ($result != null) {
            try {
                $result->role_id = $rolePermissionParam->getRoleId();
                $result->permission_id = $rolePermissionParam->getPermissionId();
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
     * @param RolePermission $rolePermission
     * @return bool
     * @throws \Exception
     */
    public function delete(int $id, RolePermission $rolePermission): bool
    {
        $result = $this->getById($id, $rolePermission);
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
     * @param int $role_id
     * @param RolePermission $rolePermission
     * @return object|null
     */
    public function getPermissionsByRoleId(int $role_id, RolePermission $rolePermission): ?object
    {
        return $rolePermission->where('role_id', $role_id)->get();
    }

    /**
     * @param int $permission_id
     * @param RolePermission $rolePermission
     * @return object|null
     */
    public function getRolesByPermissionId(int $permission_id, RolePermission $rolePermission): ?object
    {
        return $rolePermission->where('permission_id', $permission_id)->get();
    }

    /**
     * @param int $id
     * @param RolePermission $rolePermission
     * @return RolePermission|null
     */
    public function getById(int $id, RolePermission $rolePermission): ?RolePermission
    {
        return $rolePermission->find($id);
    }

    /**
     * @param RolePermission $rolePermission
     * @return object|null
     */
    public function getAllRolePermission(RolePermission $rolePermission): ?object
    {
        return $rolePermission->get();
    }


}
