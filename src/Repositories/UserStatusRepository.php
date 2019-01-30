<?php

namespace WebAppId\User\Repositories;

use Illuminate\Database\QueryException;
use WebAppId\User\Models\UserStatus;


/**
 * Class UserStatusRepository
 * @package App\Http\Repositories
 */
class UserStatusRepository
{
    /**
     * @param $request
     * @param UserStatus $userStatus
     * @return UserStatus|null
     */
    public function addUserStatus($request, UserStatus $userStatus): ?UserStatus
    {
        try {
            $userStatus->name = $request->name;
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
     * @param $name
     * @param UserStatus $userStatus
     * @return mixed
     */
    public function getByName($name, UserStatus $userStatus): ?UserStatus
    {
        return $userStatus->where('name', $name)->first();
    }
    
    /**
     * @param $id
     * @param UserStatus $userStatus
     * @return mixed
     */
    public function getStatusById($id, UserStatus $userStatus): ?UserStatus
    {
        return $userStatus->find($id);
    }
}