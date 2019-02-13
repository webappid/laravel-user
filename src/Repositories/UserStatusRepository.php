<?php

namespace WebAppId\User\Repositories;

use Illuminate\Database\QueryException;
use WebAppId\User\Models\UserStatus;
use WebAppId\User\Services\Params\UserStatusParam;


/**
 * Class UserStatusRepository
 * @package App\Http\Repositories
 */
class UserStatusRepository
{
    /**
     * @param UserStatusParam $userStatusParam
     * @param UserStatus $userStatus
     * @return UserStatus|null
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
     */
    public function getStatusById(int $id, UserStatus $userStatus): ?UserStatus
    {
        return $userStatus->find($id);
    }
}