<?php
/**
 * Created by PhpStorm.
 * User: Fadlika_N
 * Date: 3/5/2019
 * Time: 2:12 PM
 */

namespace WebAppId\User\Repositories;

use WebAppId\User\Repositories\Requests\PermissionRepositoryRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\User\Repositories\Contracts\PermissionRepositoryContract;
use WebAppId\User\Services\Params\PermissionParam;
use WebAppId\User\Models\Permission;
use Illuminate\Database\QueryException;

/**
 * Class PermissionRepository
 * @package App\Http\Repositories
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

    protected function getColumn($content)
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
            ->join('users as updated_users', 'permissions.updated_by', 'updated_users.id');
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, PermissionRepositoryRequest $permissionRepositoryRequest, Permission $permission): ?Permission
    {
        $permission = $this->getById($id, $permission);
        if($permission!=null){
            try {
                $permission = Lazy::copy($permissionRepositoryRequest, $permission);
                $permission->save();
                return $permission;
            }catch (QueryException $queryException){
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
        $permission = $this->getById($id, $permission);
        if($permission!=null){
            return $permission->delete();
        }else{
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(Permission $permission, int $length = 12): LengthAwarePaginator
    {
        return $this->getColumn($permission)->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getCount(Permission $permission): int
    {
        return $permission->count();
    }

    /**
     * @inheritDoc
     */
    public function getWhere(string $q, Permission $permission, int $length = 12): LengthAwarePaginator
    {
        return $this->getColumn($permission)
            ->where('permissions.name', 'LIKE', '%' . $q . '%')
            ->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getWhereCount(string $q, Permission $permission, int $length = 12): int
    {
        return $permission
            ->where('name', 'LIKE', '%' . $q . '%')
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function getByName(string $name, Permission $permission): ?Permission
    {
        return $permission->where('name', $name)->first();
    }

    /**
     * @param PermissionParam $permissionParam
     * @param Permission $permission
     * @return Permission|null
     * @deprecated
     */
    public function add(PermissionParam $permissionParam, Permission $permission): ?Permission
    {
        try {
            $permission->name = $permissionParam->getName();
            $permission->description = $permissionParam->getDescription();
            $permission->save();
            return $permission;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }

    /**
     * @param int $id
     * @param PermissionParam $permissionParam
     * @param Permission $permission
     * @return Permission|null
     * @deprecated
     */
    public function updateData(int $id, PermissionParam $permissionParam, Permission $permission): ?Permission
    {
        $result = $this->getById($id, $permission);
        if ($result != null) {
            try {
                $result->name = $permissionParam->getName();
                $result->description = $permissionParam->getDescription();
                $result->save();
                return $result;
            } catch (QueryException $e){
                report($e);
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * @param int $id
     * @param Permission $permission
     * @return bool
     * @throws \Exception
     * @deprecated
     */
    public function deleteData(int $id, Permission $permission): bool
    {
        $result = $this->getById($id, $permission);
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
     * @param int $id
     * @param Permission $permission
     * @return Permission|null
     * @deprecated
     */
    public function getDataById(int $id, Permission $permission): ?Permission
    {
        return $permission->find($id);
    }

    /**
     * @param Permission $permission
     * @return object|null
     * @deprecated
     */
    public function getAll(Permission $permission): ?object
    {
        return $permission->get();
    }
}
