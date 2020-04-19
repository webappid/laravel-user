<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Repositories;

use Exception;
use WebAppId\User\Models\Role;
use WebAppId\User\Repositories\Contracts\RoleRepositoryContract;
use WebAppId\User\Repositories\Requests\RoleRepositoryRequest;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\User\Services\Params\RoleParam;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 17:14:01
 * Time: 2020/04/18
 * Class RoleRepository
 * @package App\Repositories
 */
class RoleRepository implements RoleRepositoryContract
{
    /**
     * @inheritDoc
     */
    public function store(RoleRepositoryRequest $roleRepositoryRequest, Role $role): ?Role
    {
        try {
            $role = Lazy::copy($roleRepositoryRequest, $role);
            $role->save();
            return $role;
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
                'roles.id',
                'roles.name',
                'roles.description'
            );
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, RoleRepositoryRequest $roleRepositoryRequest, Role $role): ?Role
    {
        $role = $this->getById($id, $role);
        if ($role != null) {
            try {
                $role = Lazy::copy($roleRepositoryRequest, $role);
                $role->save();
                return $role;
            } catch (QueryException $queryException) {
                report($queryException);
            }
        }
        return $role;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, Role $role): ?Role
    {
        return $this->getColumn($role)->find($id);
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, Role $role): bool
    {
        $role = $this->getById($id, $role);
        if ($role != null) {
            return $role->delete();
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(Role $role, int $length = 12): LengthAwarePaginator
    {
        return $this->getColumn($role)->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getCount(Role $role): int
    {
        return $role->count();
    }

    /**
     * @inheritDoc
     */
    public function getWhere(string $q, Role $role, int $length = 12): LengthAwarePaginator
    {
        return $this->getColumn($role)
            ->where('name', 'LIKE', '%' . $q . '%')
            ->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getWhereCount(string $q, Role $role, int $length = 12): int
    {
        return $role
            ->where('name', 'LIKE', '%' . $q . '%')
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function getByName(string $name, Role $role): ?Role
    {
        return $role->where('name', $name)->first();
    }

    /**
     * @param Role $role
     * @param RoleParam $request
     * @return Role|null
     * @deprecated
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
     * @deprecated
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
     * @throws Exception
     * @deprecated
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
     * @deprecated
     */
    public function getRoleByName(string $name, Role $role): ?Role
    {
        return $role->where('name', $name)->first();
    }

    /**
     * @param Role $role
     * @return object|null
     * @deprecated
     */
    public function getAllRole(Role $role): ?object
    {
        return $role->get();
    }

    /**
     * @param int $id
     * @param Role $role
     * @return Role|null
     * @deprecated
     */
    public function getRoleById(int $id, Role $role): ?Role
    {
        return $role->find($id);
    }
}
