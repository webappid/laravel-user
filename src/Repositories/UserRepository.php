<?php

namespace WebAppId\User\Repositories;


use Illuminate\Database\QueryException;
use WebAppId\User\Models\User;

/**
 * Class UserRepository
 * @package App\Http\Repositories
 */
class UserRepository
{
    /**
     * @param $request
     * @param User $user
     * @return User|null
     */
    public function addUser($request, User $user)
    {
        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->status_id = $request->status_id;
            $user->password = bcrypt($request->password);
            $user->save();
            return $user;
        } catch (QueryException $e) {
            report($e);
            return null;
        }
    }
    
    /**
     * @param $email
     * @param User $user
     * @return mixed
     */
    public function getUserByEmail($email, User $user)
    {
        return $user->where('email', $email)->first();
    }
    
    /**
     * @param $email
     * @param $password
     * @param User $user
     * @return User|null
     */
    public function setUpdatePassword($email, $password, User $user)
    {
        $user = $this->getUserByEmail($email, $user);
        if ($user != null) {
            try {
                $user->password = bcrypt($password);
                $user->save();
                return $user;
            } catch (QueryException $queryException) {
                report($queryException);
                return null;
            }
        } else {
            return null;
        }
    }
    
    
    /**
     * @param $email
     * @param $status
     * @param User $user
     * @return User|mixed|null
     */
    public function setUpdateStatusUser($email, $status, User $user)
    {
        $user = $this->getUserByEmail($email, $user);
        if ($user != null) {
            try {
                $user->status_id = $status;
                $user->save();
                return $user;
            } catch (QueryException $queryException) {
                report($queryException);
                return null;
            }
        }else{
            return null;
        }
    }
}