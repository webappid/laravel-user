<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Repositories;

use Illuminate\Database\Eloquent\Builder;
use WebAppId\User\Models\Role;
use WebAppId\User\Repositories\Contracts\RoleRepositoryContract;
use WebAppId\User\Repositories\Requests\RoleRepositoryRequest;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\DDD\Tools\Lazy;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 17:14:01
 * Time: 2020/04/18
 * Class RoleRepository
 * @package WebAppId\User\Repositories
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

    protected function getColumn($content, string $q = null): Builder
    {
        return $content
            ->select
            (
                'roles.id',
                'roles.name',
                'roles.description'
            )->when($q != null, function ($query) use ($q) {
                return $query->where('name', 'LIKE', '%' . $q . '%');
            });
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, RoleRepositoryRequest $roleRepositoryRequest, Role $role): ?Role
    {
        $role = $role->find($id);
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
        $role = $role->find($id);
        if ($role != null) {
            return $role->delete();
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(Role $role, int $length = 12, string $q = null): LengthAwarePaginator
    {
        return $this->getColumn($role, $q)->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getCount(Role $role, string $q = null): int
    {
        return $this->getColumn($role, $q)->count();
    }

    /**
     * @inheritDoc
     */
    public function getByName(string $name, Role $role): ?Role
    {
        return $role->where('name', $name)->first();
    }
}
