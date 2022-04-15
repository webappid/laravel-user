<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\User\Repositories;


use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Lazy\Tools\Lazy;
use WebAppId\Lazy\Traits\RepositoryTrait;
use WebAppId\User\Models\RolePermission;
use WebAppId\User\Repositories\Requests\RolePermissionRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20/09/2020
 * Time: 19.02
 * Class RolePermissionRepositoryTrait
 * @package WebAppId\User\Repositories
 */
trait RolePermissionRepositoryTrait
{

    use RepositoryTrait;

    /**
     * @param RolePermissionRepositoryRequest $rolePermissionRepositoryRequest
     * @param RolePermission $rolePermission
     * @return object|null
     */
    public function store(RolePermissionRepositoryRequest $rolePermissionRepositoryRequest, RolePermission $rolePermission): ?object
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

    /**
     * @param int $id
     * @param RolePermissionRepositoryRequest $rolePermissionRepositoryRequest
     * @param RolePermission $rolePermission
     * @return object
     */
    public function update(int $id, RolePermissionRepositoryRequest $rolePermissionRepositoryRequest, RolePermission $rolePermission): object
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
     * @param int $id
     * @param RolePermission $rolePermission
     * @return RolePermission|null
     */
    public function getById(int $id, RolePermission $rolePermission): ?RolePermission
    {
        return $this->getJoin($rolePermission)->find($id, $this->getColumn());
    }

    /**
     * @param int $id
     * @param RolePermission $rolePermission
     * @return bool
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
     * @param RolePermission $rolePermission
     * @param int $length
     * @return LengthAwarePaginator
     */
    public function get(RolePermission $rolePermission, int $length = 12): LengthAwarePaginator
    {
        return $this->getJoin($rolePermission)
            ->paginate($length, $this->getColumn())
            ->appends(request()->input());
    }

    /**
     * @param RolePermission $rolePermission
     * @return int
     */
    public function getCount(RolePermission $rolePermission): int
    {
        return $rolePermission->count();
    }
}