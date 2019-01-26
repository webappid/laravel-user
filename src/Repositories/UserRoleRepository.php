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

/**
 * Class UserRoleRepository
 * @package App\Http\Repositories
 */
class UserRoleRepository
{
    /**
     * @param $request
     * @param UserRole $userRole
     * @return UserRole|null
     */
    public function addUserRole($request, UserRole $userRole)
    {
        try {
            $userRole->user_id = $request->user_id;
            $userRole->role_id = $request->role_id;
            $userRole->save();
            return $userRole;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }
}