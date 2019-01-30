<?php

namespace WebAppId\User\Repositories;


use Illuminate\Database\QueryException;
use WebAppId\User\Models\User;
use WebAppId\User\Services\Params\UserParam;
use WebAppId\User\Services\Params\UserSearchParam;

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
     * @param UserSearchParam $userSearchParam
     * @param User $user
     * @return int|null
     */
    public function getUserSearchCount(UserSearchParam $userSearchParam, User $user): ?int
    {
        return $this->getUserQuery($user, $userSearchParam->getQ())->count();
    }
    
    /**
     * @param User $user
     * @return int|null
     */
    public function getCountAllUser(User $user): ?int
    {
        return $user->count();
    }
    
    /**
     * @param UserSearchParam $userSearchParam
     * @param User $user
     * @param $paginate
     * @return object|null
     */
    public function getUserSearch(UserSearchParam $userSearchParam, User $user, $paginate = '12'): ?object
    {
        return $this->getUserQuery($user, $userSearchParam->getQ())->paginate($paginate);
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
    
    /**
     * @param string $email
     * @param string $name
     * @param User $user
     * @return User|null
     */
    public function setUpdateName(string $email, string $name, User $user): ?User
    {
        $user = $this->getUserByEmail($email, $user);
        if ($user != null) {
            try {
                $user->name = $name;
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
     * @param string $email
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function deleteUserByEmail(string $email, User $user): bool
    {
        $user = $this->getUserByEmail($email, $user);
        try {
            return $user->delete();
        } catch (QueryException $queryException) {
            report($queryException);
            return false;
        }
    }
}