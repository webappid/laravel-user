<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */


namespace WebAppId\User\Repositories;

use WebAppId\User\Repositories\Contracts\UserStatusRepositoryContract;
use WebAppId\User\Repositories\Requests\UserStatusRepositoryRequest;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\User\Models\UserStatus;
use WebAppId\User\Services\Params\UserStatusParam;


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

    protected function getColumn($content)
    {
        return $content
            ->select
            (
                'user_statuses.name'
            );
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
    public function get(UserStatus $userStatus, int $length = 12): LengthAwarePaginator
    {
        return $this->getColumn($userStatus)->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getCount(UserStatus $userStatus): int
    {
        return $userStatus->count();
    }

    /**
     * @inheritDoc
     */
    public function getWhere(string $q, UserStatus $userStatus, int $length = 12): LengthAwarePaginator
    {
        return $this->getColumn($userStatus)
            ->where('name', 'LIKE', '%' . $q . '%')
            ->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getWhereCount(string $q, UserStatus $userStatus, int $length = 12): int
    {
        return $userStatus
            ->where('name', 'LIKE', '%' . $q . '%')
            ->count();
    }
    
    /**
     * @param UserStatusParam $userStatusParam
     * @param UserStatus $userStatus
     * @return UserStatus|null
     * @deprecated 
     */
    public function addUserStatus(UserStatusParam $userStatusParam, UserStatus $userStatus): ?UserStatus
    {
        try {
            $userStatus->name = $userStatusParam->getName();
            $userStatus->save();
            return $userStatus;
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }
    
    /**
     * @param UserStatus $userStatus
     * @return UserStatus[]|\Illuminate\Database\Eloquent\Collection
     * @deprecated 
     */
    public function getAll(UserStatus $userStatus): ?object
    {
        return $userStatus->all();
    }
    
    /**
     * @param string $name
     * @param UserStatus $userStatus
     * @return UserStatus|null
     */
    public function getByName(string $name, UserStatus $userStatus): ?UserStatus
    {
        return $userStatus->where('name', $name)->first();
    }
    
    /**
     * @param int $id
     * @param UserStatus $userStatus
     * @return UserStatus|null
     * @deprecated 
     */
    public function getStatusById(int $id, UserStatus $userStatus): ?UserStatus
    {
        return $userStatus->find($id);
    }
}
