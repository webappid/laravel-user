<?php
/**
 * Created by PhpStorm.
 * Users: dyangalih
 * Date: 05/11/18
 * Time: 01.58
 */

namespace WebAppId\User\Repositories;


use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\User\Models\UserRole;
use WebAppId\User\Repositories\Contracts\UserRoleRepositoryContract;
use WebAppId\User\Repositories\Requests\UserRoleRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 19/04/20
 * Time: 04.36
 * Class UserRoleRepository
 * @package WebAppId\User\Repositories
 */
class UserRoleRepository implements UserRoleRepositoryContract
{
    /**
     * @inheritDoc
     */
    public function store(UserRoleRepositoryRequest $userRoleRepositoryRequest, UserRole $userRole): ?UserRole
    {
        try {
            $userRole = Lazy::copy($userRoleRepositoryRequest, $userRole);
            $userRole->save();
            return $userRole;
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
                'user_roles.id',
                'user_roles.user_id',
                'user_roles.role_id',
                'roles.name',
                'roles.description',
                'users.name',
                'users.email'
            )
            ->join('roles as roles', 'user_roles.role_id', 'roles.id')
            ->join('users as users', 'user_roles.user_id', 'users.id');
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, UserRoleRepositoryRequest $userRoleRepositoryRequest, UserRole $userRole): ?UserRole
    {
        $userRole = $userRole->first($id);
        if ($userRole != null) {
            try {
                $userRole = Lazy::copy($userRoleRepositoryRequest, $userRole);
                $userRole->save();
                return $userRole;
            } catch (QueryException $queryException) {
                report($queryException);
            }
        }
        return $userRole;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, UserRole $userRole): ?UserRole
    {
        return $this->getColumn($userRole)->find($id);
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, UserRole $userRole): bool
    {
        $userRole = $userRole->find($id);
        if ($userRole != null) {
            return $userRole->delete();
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(UserRole $userRole, int $length = 12): LengthAwarePaginator
    {
        return $this->getColumn($userRole)->paginate($length)
            ->appends(request()->input());
    }

    /**
     * @inheritDoc
     */
    public function getCount(UserRole $userRole): int
    {
        return $userRole->count();
    }

    /**
     * @inheritDoc
     */
    public function deleteByUserId(int $user_id, UserRole $userRole): bool
    {
        try {
            return $userRole->where('user_id', $user_id)->delete();
        } catch (QueryException $queryException) {
            report($queryException);
            return false;
        }
    }
}
