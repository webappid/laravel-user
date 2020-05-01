<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */


namespace WebAppId\User\Repositories;

use Illuminate\Database\Eloquent\Builder;
use WebAppId\User\Repositories\Contracts\UserStatusRepositoryContract;
use WebAppId\User\Repositories\Requests\UserStatusRepositoryRequest;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\User\Models\UserStatus;


/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 18/04/20
 * Time: 22.07
 * Class UserStatusRepository
 * @package WebAppId\User\Repositories
 */
class UserStatusRepository implements UserStatusRepositoryContract
{
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

    protected function getColumn($content, string $q = null): Builder
    {
        return $content
            ->select
            (
                'user_statuses.id',
                'user_statuses.name'
            )->when($q!=null, function($query) use ($q){
                return $query->where('name', 'LIKE', '%' . $q . '%');
            });
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, UserStatusRepositoryRequest $userStatusRepositoryRequest, UserStatus $userStatus): ?UserStatus
    {
        $userStatus = $this->getById($id, $userStatus);
        if($userStatus!=null){
            try {
                $userStatus = Lazy::copy($userStatusRepositoryRequest, $userStatus);
                $userStatus->save();
                return $userStatus;
            }catch (QueryException $queryException){
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
        return $this->getColumn($userStatus)->find($id);
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, UserStatus $userStatus): bool
    {
        $userStatus = $this->getById($id, $userStatus);
        if($userStatus!=null){
            return $userStatus->delete();
        }else{
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(UserStatus $userStatus, int $length = 12, string $q = null): LengthAwarePaginator
    {
        return $this->getColumn($userStatus, $q)->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getCount(UserStatus $userStatus, string $q = null): int
    {
        return $this->getColumn($userStatus, $q)->count();
    }
    
    /**
     * @inheritDoc
     */
    public function getByName(string $name, UserStatus $userStatus): ?UserStatus
    {
        return $userStatus->where('name', $name)->first();
    }
}
