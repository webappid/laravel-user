<?php

namespace WebAppId\User\Repositories;

use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Auth\Authenticatable;
use InvalidArgumentException;
use WebAppId\User\Models\User;
use WebAppId\User\Services\Params\UserParam;
use WebAppId\User\Services\Params\UserSearchParam;

/**
 * Class UserRepository
 * @package App\Http\Repositories
 */
class UserRepository
{
    protected function getColumn(User $user)
    {
        return $user->select(
            'users.id AS id',
            'users.name AS name',
            'users.email AS email',
            'users.status_id AS status_id',
            'users.password AS password',
            'user_statuses.name AS status',
            'users.remember_token AS remember_token')
            ->leftJoin('user_statuses', 'user_statuses.id', '=', 'users.status_id');
    }

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
    public function getUserByEmail(string $email, User $user): ?User
    {
        return $this->getColumn($user)
            ->where('email', $email)
            ->first();
    }

    /**
     * @param User $user
     * @param $search
     * @return mixed
     */
    public function getUserQuery(User $user, string $search)
    {
        return $this->getColumn($user)
            ->where(function ($query) use ($search) {
                $query->where('users.name', 'LIKE', '%' . $search . '%')
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
    public function setUpdatePassword(string $email, string $password, User $user): ?User
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

    /**
     * @param string $email
     * @param Application $application
     * @param User $user
     * @return string
     */
    public function setResetPasswordTokenByEmail(string $email, Application $application, User $user): string
    {
        $user = $this->getUserByEmail($email, $user);

        $key = env('APP_KEY');

        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        $name = "users";

        $config = $application['config']["auth.passwords.{$name}"];

        if (is_null($config)) {
            throw new InvalidArgumentException("Password resetter [{$name}] is not defined.");
        }

        $connection = $config['connection'] ?? null;
        $tokens = new DatabaseTokenRepository(
            $application['db']->connection($connection),
            $application['hash'],
            $config['table'],
            $key,
            $config['expire']
        );

        if ($user != null) {
            $token = $tokens->createNewToken();
            Cache::put($token, $user->email, $config['expire']);
            $user->sendPasswordResetNotification($token);
        }

        return $token;
    }
}
