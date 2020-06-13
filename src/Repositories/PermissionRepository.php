<?php
/**
 * Created by PhpStorm.
 * User: Fadlika_N
 * Date: 3/5/2019
 * Time: 2:12 PM
 */

namespace WebAppId\User\Repositories;

use Illuminate\Database\Eloquent\Builder;
use WebAppId\User\Repositories\Requests\PermissionRepositoryRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\User\Repositories\Contracts\PermissionRepositoryContract;
use WebAppId\User\Models\Permission;
use Illuminate\Database\QueryException;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 28/04/2020
 * Time: 22.39
 * Class PermissionRepository
 * @package WebAppId\User\Repositories
 */
class PermissionRepository implements PermissionRepositoryContract
{
    /**
     * @inheritDoc
     */
    public function store(PermissionRepositoryRequest $permissionRepositoryRequest, Permission $permission): ?Permission
    {
        try {
            $permission = Lazy::copy($permissionRepositoryRequest, $permission);
            $permission->save();
            return $permission;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }

    protected function getColumn($content, string $q = null): Builder
    {
        return $content
            ->select
            (
                'permissions.id',
                'permissions.name',
                'permissions.description',
                'permissions.created_by',
                'permissions.updated_by',
                'permissions.created_at',
                'permissions.updated_at',
                'users.id',
                'users.name AS user_name',
                'updated_users.id AS updated_id',
                'updated_users.name AS updated_name'
            )
            ->join('users as users', 'permissions.created_by', 'users.id')
            ->join('users as updated_users', 'permissions.updated_by', 'updated_users.id')
            ->when($q != null, function ($query) use ($q) {
                return $query->where('permissions.name', 'LIKE', '%' . $q . '%');
            });
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, PermissionRepositoryRequest $permissionRepositoryRequest, Permission $permission): ?Permission
    {
        $permission = $permission->first($id);
        if ($permission != null) {
            try {
                $permission = Lazy::copy($permissionRepositoryRequest, $permission);
                $permission->save();
                return $permission;
            } catch (QueryException $queryException) {
                report($queryException);
            }
        }
        return $permission;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, Permission $permission): ?Permission
    {
        return $this->getColumn($permission)->find($id);
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, Permission $permission): bool
    {
        $permission = $permission->find($id);
        if ($permission != null) {
            return $permission->delete();
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(Permission $permission, int $length = 12, string $q = null): LengthAwarePaginator
    {
        return $this
            ->getColumn($permission, $q)
            ->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getCount(Permission $permission, string $q = null): int
    {
        return $this->getColumn($permission, $q)->count();
    }

    /**
     * @inheritDoc
     */
    public function getByName(string $name, Permission $permission): ?Permission
    {
        return $permission->where('name', $name)->first();
    }
}
