<?php

namespace WebAppId\User\Repositories;


use Illuminate\Database\QueryException;
use WebAppId\User\Models\User;
use WebAppId\User\Services\Params\UserParam;

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
    public function addUser(UserParam $request, User $user): ?User
    {
        try {
            $user->name = $request->getName();
            $user->email = $request->getEmail();
            $user->status_id = $request->getStatusId();
            $user->password = bcrypt($request->getPassword());
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
     * @return User|null
     */
    public function getUserByEmail($email, User $user): ?User
    {
        return $user->where('email', $email)->first();
    }
    
    /**
     * @param User $user
     * @param $search
     * @return mixed
     */
    public function getUserQuery(User $user, $search)
    {
        return $user->where(function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('email', $search);
        });
    }
    
    /**
     * @param $request
     * @param User $user
     * @return mixed
     */
    public function getUserSearchCount($request, User $user): ?int
    {
        return $this->getUserQuery($user, $request->q)->count();
    }
    
    /**
     * @param User $user
     * @return mixed
     */
    public function getCountAllUser(User $user): ?int
    {
        return $user->count();
    }
    
    /**
     * @param $request
     * @param User $user
     * @param $paginate
     * @return mixed
     */
    public function getUserSearch($request, User $user, $paginate): ?object
    {
        return $this->getUserQuery($user, $request->q)->paginate($paginate);
    }
    
    /**
     * @param $email
     * @param $password
     * @param User $user
     * @return User|null
     */
    public function setUpdatePassword($email, $password, User $user): ?User
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
    public function setUpdateStatusUser(string $email, int $status, User $user): ?User
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
        } else {
            return null;
        }
    }
}