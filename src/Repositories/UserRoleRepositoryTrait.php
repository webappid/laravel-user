<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\User\Repositories;


use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\Lazy\Tools\Lazy;
use WebAppId\Lazy\Traits\RepositoryTrait;
use WebAppId\User\Models\UserRole;
use WebAppId\User\Repositories\Requests\UserRoleRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20/09/2020
 * Time: 19.23
 * Class UserRoleRepositoryTrait
 * @package WebAppId\User\Repositories
 */
trait UserRoleRepositoryTrait
{
    use RepositoryTrait;

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
        return $this
            ->getJoin($userRole)
            ->find($id, $this->getColumn());
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
        return $this
            ->getJoin($userRole)
            ->paginate($length, $this->getColumn())
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