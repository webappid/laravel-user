<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\User\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Lazy\Tools\Lazy;
use WebAppId\Lazy\Traits\RepositoryTrait;
use WebAppId\User\Models\Permission;
use WebAppId\User\Repositories\Requests\PermissionRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20/09/2020
 * Time: 18.50
 * Class PermissionRepositoryTrait
 * @package WebAppId\User\Repositories
 */
trait PermissionRepositoryTrait
{

    use RepositoryTrait;

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
        return $this->getJoin($permission)->find($id, $this->getColumn());
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
            ->getJoin($permission)
            ->when($q != null, function ($query) use ($q) {
                return $this->getFilter($query, $q);
            })
            ->paginate($length, $this->getColumn())
            ->appends(request()->input());
    }

    /**
     * @param Builder $query
     * @param string $q
     * @return Builder
     */
    public function getFilter(Builder $query, string $q): Builder
    {
        return $query->where('permissions.name', 'LIKE', '%' . $q . '%');
    }

    /**
     * @inheritDoc
     */
    public function getCount(Permission $permission, string $q = null): int
    {
        return $this
            ->getJoin($permission)
            ->when($q != null, function ($query) use ($q) {
                return $this->getFilter($query, $q);
            })->count();
    }

    /**
     * @inheritDoc
     */
    public function getByName(string $name, Permission $permission): ?Permission
    {
        return $permission->where('name', $name)->first();
    }
}