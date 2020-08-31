<?php
/**
 * Created by PhpStorm.
 * User: Fadlika_N
 * Date: 3/6/2019
 * Time: 1:01 PM
 */

namespace WebAppId\User\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\User\Models\RolePermission;
use WebAppId\User\Repositories\Contracts\RolePermissionRepositoryContract;
use WebAppId\User\Repositories\Requests\RolePermissionRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 28/04/2020
 * Time: 22.38
 * Class RolePermissionRepository
 * @package WebAppId\User\Repositories
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

    protected function getColumn($content): Builder
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
        $rolePermission = $rolePermission->first($id);
        if ($rolePermission != null) {
            try {
                $rolePermission = Lazy::copy($rolePermissionRepositoryRequest, $rolePermission);
                $rolePermission->save();
                return $rolePermission;
            } catch (QueryException $queryException) {
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
        $rolePermission = $rolePermission->find($id);
        if ($rolePermission != null) {
            return $rolePermission->delete();
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(RolePermission $rolePermission, int $length = 12): LengthAwarePaginator
    {
        return $this->getColumn($rolePermission)->paginate($length)
            ->appends(request()->input());
    }

    /**
     * @inheritDoc
     */
    public function getCount(RolePermission $rolePermission): int
    {
        return $rolePermission->count();
    }
}
