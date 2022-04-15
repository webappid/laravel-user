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
use WebAppId\User\Models\Role;
use WebAppId\User\Repositories\Requests\RoleRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20/09/2020
 * Time: 19.07
 * Class RoleRepositoryTrait
 * @package WebAppId\User\Repositories
 */
trait RoleRepositoryTrait
{
    use RepositoryTrait;

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
        return $this
            ->getJoin($role)
            ->find($id, $this->getColumn());
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
        return $this->getJoin($role)
            ->when($q != null, function ($query) use ($q) {
                return $this->getFilter($query, $q);
            })
            ->paginate($length, $this->getColumn())
            ->appends(request()->input());
    }

    /**
     * @inheritDoc
     */
    public function getCount(Role $role, string $q = null): int
    {
        return $this->getJoin($role)
            ->when($q != null, function ($query) use ($q) {
                return $this->getFilter($query, $q);
            })
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
     * @param Builder $query
     * @param string $q
     * @return Builder
     */
    protected function getFilter(Builder $query, string $q): Builder
    {
        return $query->where('name', 'LIKE', '%' . $q . '%');
    }
}