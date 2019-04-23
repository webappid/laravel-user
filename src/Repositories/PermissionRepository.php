<?php
/**
 * Created by PhpStorm.
 * User: Fadlika_N
 * Date: 3/5/2019
 * Time: 2:12 PM
 */

namespace WebAppId\User\Repositories;

use WebAppId\User\Services\Params\PermissionParam;
use WebAppId\User\Models\Permission;
use Illuminate\Database\QueryException;

/**
 * Class PermissionRepository
 * @package App\Http\Repositories
 */
class PermissionRepository
{
    /**
     * @param PermissionParam $permissionParam
     * @param Permission $permission
     * @return Permission|null
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
     */
    public function update(int $id, PermissionParam $permissionParam, Permission $permission): ?Permission
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
     */
    public function delete(int $id, Permission $permission): bool
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
     * @param string $name
     * @param Permission $permission
     * @return Permission|null
     */
    public function getByName(string $name, Permission $permission): ?Permission
    {
        return $permission->where('name', $name)->first();
    }

    /**
     * @param int $id
     * @param Permission $permission
     * @return Permission|null
     */
    public function getById(int $id, Permission $permission): ?Permission
    {
        return $permission->find($id);
    }

    /**
     * @param Permission $permission
     * @return object|null
     */
    public function getAll(Permission $permission): ?object
    {
        return $permission->get();
    }
}
