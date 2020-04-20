<?php
/**
 * Created by PhpStorm.
 * User: Fadlika_N
 * Date: 3/6/2019
 * Time: 1:01 PM
 */

namespace WebAppId\User\Repositories;

use WebAppId\User\Repositories\Requests\RolePermissionRepositoryRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\User\Models\RolePermission;
use WebAppId\User\Repositories\Contracts\RolePermissionRepositoryContract;
use WebAppId\User\Services\Params\RolePermissionParam;
use Illuminate\Database\QueryException;

/**
 * Class RolePermissionRepository
 * @package WebAppId\User\Http\Repositories\Contract
 */
class RolePermissionRepository implements RolePermissionRepositoryContract
{
    /**
     * @inheritDoc
     */
    public function store(RolePermissionRepositoryRequest $rolePermissionRepositoryRequest, RolePermission $rolePermission): ?RolePermission
    {
        try {
            $rolePermission = Lazy::copy($rolePermissionRepositoryRequest, $rolePermission);
            $rolePermission->save();
            return $rolePermission;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }

    protected function getColumn($content)
    {
        return $content
            ->select
            (
                'role_permissions.id',
                'role_permissions.role_id',
                'role_permissions.permission_id',
                'users.name',
                'permissions.id',
                'permissions.name',
                'permissions.description',
                'roles.id',
                'roles.name',
                'roles.description',
                'updated_users.id AS updated_id',
                'updated_users.name AS updated_name'
            )
            ->join('users as users', 'role_permissions.created_by', 'users.id')
            ->join('permissions as permissions', 'role_permissions.permission_id', 'permissions.id')
            ->join('roles as roles', 'role_permissions.role_id', 'roles.id')
            ->join('users as updated_users', 'role_permissions.updated_by', 'updated_users.id');
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, RolePermissionRepositoryRequest $rolePermissionRepositoryRequest, RolePermission $rolePermission): ?RolePermission
    {
        $rolePermission = $this->getById($id, $rolePermission);
        if($rolePermission!=null){
            try {
                $rolePermission = Lazy::copy($rolePermissionRepositoryRequest, $rolePermission);
                $rolePermission->save();
                return $rolePermission;
            }catch (QueryException $queryException){
                report($queryException);
            }
        }
        return $rolePermission;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, RolePermission $rolePermission): ?RolePermission
    {
        return $this->getColumn($rolePermission)->find($id);
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, RolePermission $rolePermission): bool
    {
        $rolePermission = $this->getById($id, $rolePermission);
        if($rolePermission!=null){
            return $rolePermission->delete();
        }else{
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(RolePermission $rolePermission, int $length = 12): LengthAwarePaginator
    {
        return $this->getColumn($rolePermission)->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getCount(RolePermission $rolePermission): int
    {
        return $rolePermission->count();
    }

    /**
     * @param RolePermissionParam $rolePermissionParam
     * @param RolePermission $rolePermission
     * @return RolePermission|null
     * @deprecated
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
     * @param int $role_id
     * @param RolePermission $rolePermission
     * @return bool|null
     */
    public function deleteByRoleId(int $role_id, RolePermission $rolePermission): ?bool
    {
        try {
            return $rolePermission->where('role_id', $role_id)->delete();
        } catch (QueryException $queryException) {
            report($queryException);
            return false;
        }
    }

    /**
     * @param int $role_id
     * @param int $permission_id
     * @param RolePermission $rolePermission
     * @return bool|null
     */
    public function deleteByRoleIdPermissionId(int $role_id, int $permission_id, RolePermission $rolePermission): ?bool
    {
        try {
            return $rolePermission->where('role_id', $role_id)->where('permission_id', $permission_id)->delete();
        } catch (QueryException $queryException) {
            report($queryException);
            return false;
        }
    }

    /**
     * @param RolePermission $rolePermission
     * @return object|null
     * @deprecated
     */
    public function getAllRolePermission(RolePermission $rolePermission): ?object
    {
        return $rolePermission->get();
    }


}
