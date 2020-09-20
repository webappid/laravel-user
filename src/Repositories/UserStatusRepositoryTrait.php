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
use WebAppId\User\Models\UserStatus;
use WebAppId\User\Repositories\Requests\UserStatusRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20/09/2020
 * Time: 19.28
 * Class UserStatusRepositoryTrait
 * @package WebAppId\User\Repositories
 */
trait UserStatusRepositoryTrait
{
    use RepositoryTrait;

    /**
     * @inheritDoc
     */
    public function store(UserStatusRepositoryRequest $userStatusRepositoryRequest, UserStatus $userStatus): ?UserStatus
    {
        try {
            $userStatus = Lazy::copy($userStatusRepositoryRequest, $userStatus);
            $userStatus->save();
            return $userStatus;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, UserStatusRepositoryRequest $userStatusRepositoryRequest, UserStatus $userStatus): ?UserStatus
    {
        $userStatus = $userStatus->first($id);
        if ($userStatus != null) {
            try {
                $userStatus = Lazy::copy($userStatusRepositoryRequest, $userStatus);
                $userStatus->save();
                return $userStatus;
            } catch (QueryException $queryException) {
                report($queryException);
            }
        }
        return $userStatus;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, UserStatus $userStatus): ?UserStatus
    {
        return $this
            ->getJoin($userStatus)
            ->find($id, $this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, UserStatus $userStatus): bool
    {
        $userStatus = $userStatus->find($id);
        if ($userStatus != null) {
            return $userStatus->delete();
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(UserStatus $userStatus, int $length = 12, string $q = null): LengthAwarePaginator
    {
        return $this
            ->getJoin($userStatus)
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
        return $query->where('name', 'LIKE', '%' . $q . '%');
    }

    /**
     * @inheritDoc
     */
    public function getCount(UserStatus $userStatus, string $q = null): int
    {
        return $this
            ->getJoin($userStatus)
            ->when($q != null, function ($query) use ($q) {
                return $this->getFilter($query, $q);
            })
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function getByName(string $name, UserStatus $userStatus): ?UserStatus
    {
        return $userStatus->where('name', $name)->first();
    }
}