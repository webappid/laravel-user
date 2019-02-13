<?php
/**
 * Created by PhpStorm.
 * Users: dyangalih
 * Date: 05/11/18
 * Time: 01.58
 */

namespace WebAppId\User\Repositories;


use Illuminate\Database\QueryException;
use WebAppId\User\Models\UserRole;
use WebAppId\User\Services\Params\UserRoleParam;

/**
 * Class UserRoleRepository
 * @package App\Http\Repositories
 */
class UserRoleRepository
{
    /**
     * @param UserRoleParam $request
     * @param UserRole $userRole
     * @return UserRole|null
     */
    public function addUserRole(UserRoleParam $request,
                                UserRole $userRole): ?UserRole
    {
        try {
            $userRole->user_id = $request->getUserId();
            $userRole->role_id = $request->getRoleId();
            $userRole->save();
            return $userRole;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }
    
    /**
     * @param string $userId
     * @param UserRole $userRole
     * @return bool|null
     */
    public function deleteUserRoleByUserId(string $userId,
                                           UserRole $userRole): ?bool
    {
        try {
            return $userRole->where('user_id', $userId)->delete();
        } catch (QueryException $queryException) {
            report($queryException);
            return false;
        }
    }
}