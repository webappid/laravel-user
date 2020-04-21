<?php

namespace WebAppId\User\Repositories;

use WebAppId\User\Repositories\Requests\UserRepositoryRequest;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use InvalidArgumentException;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\User\Models\User;
use WebAppId\User\Repositories\Contracts\UserRepositoryContract;
use WebAppId\User\Services\Params\UserParam;
use WebAppId\User\Services\Params\UserSearchParam;

/**
 * Class UserRepository
 * @package WebAppId\User\Http\Repositories
 */
class UserRepository implements UserRepositoryContract
{
    /**
     * @inheritDoc
     */
    public function store(UserRepositoryRequest $userRepositoryRequest, User $user): ?User
    {
        try {
            $user = Lazy::copy($userRepositoryRequest, $user);
            $user->password = bcrypt($userRepositoryRequest->password);
            $user->save();
            return $user;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, UserRepositoryRequest $userRepositoryRequest, User $user): ?User
    {
        $user = $this->getById($id, $user);
        if ($user != null) {
            try {
                if ($userRepositoryRequest->password != null) {
                    $userRepositoryRequest->password = $user->password;
                } else {
                    $userRepositoryRequest->password = bcrypt($userRepositoryRequest->password);
                }
                $user = Lazy::copy($userRepositoryRequest, $user);

                $user->save();
                return $user;
            } catch (QueryException $queryException) {
                report($queryException);
            }
        }
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, User $user): ?User
    {
        return $this->getColumn($user)->find($id);
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, User $user): bool
    {
        $user = $this->getById($id, $user);
        if ($user != null) {
            return $user->delete();
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(User $user, int $length = 12): LengthAwarePaginator
    {
        return $this->getColumn($user)->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getCount(User $user): int
    {
        return $user->count();
    }

    private function getQueryWhere(string $q, User $user)
    {
        return $this->getColumn($user)
            ->where('users.name', 'LIKE', '%' . $q . '%')
            ->orWhere('users.email', $q);
    }

    /**
     * @inheritDoc
     */
    public function getWhere(string $q, User $user, int $length = 12): LengthAwarePaginator
    {
        return $this
            ->getQueryWhere($q, $user)
            ->paginate($length);
    }

    /**
     * @inheritDoc
     */
    public function getWhereCount(string $q, User $user, int $length = 12): int
    {
        return $this
            ->getQueryWhere($q, $user)
            ->count();
    }

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
     * @inheritDoc
     */
    public function getByEmail(string $email, User $user): ?User
    {
        return $this->getColumn($user)
            ->where('email', $email)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function setUpdatePassword(string $email, string $password, User $user): ?User
    {
        $user = $this->getByEmail($email, $user);
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
     * @inheritDoc
     */
    public function setUpdateStatusUser(string $email, int $status, User $user): ?User
    {
        $user = $this->getByEmail($email, $user);
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
     * @inheritDoc
     */
    public function setUpdateName(string $email, string $name, User $user): ?User
    {
        $user = $this->getByEmail($email, $user);
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
     * @inheritDoc
     */
    public function deleteByEmail(string $email, User $user): bool
    {
        $user = $this->getByEmail($email, $user);
        try {
            return $user->delete();
        } catch (QueryException $queryException) {
            report($queryException);
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function setResetPasswordTokenByEmail(string $email, Application $application, User $user): string
    {
        $user = $this->getByEmail($email, $user);

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

    /**
     * @param $email
     * @param User $user
     * @return User|null
     * @deprecated
     */
    public function getUserByEmail(string $email, User $user): ?User
    {
        return $this->getColumn($user)
            ->where('email', $email)
            ->first();
    }

    /**
     * @param $request
     * @param User $user
     * @return User|null
     * @deprecated
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
     * @param User $user
     * @param $search
     * @return mixed
     */
    private function getUserQuery(User $user, string $search)
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
     * @deprecated
     */
    public function getUserSearchCount(UserSearchParam $userSearchParam, User $user): ?int
    {
        return $this->getUserQuery($user, $userSearchParam->getQ())->count();
    }

    /**
     * @param User $user
     * @return int|null
     * @deprecated
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
     * @deprecated
     */
    public function getUserSearch(UserSearchParam $userSearchParam, User $user, $paginate = '12'): ?object
    {
        return $this->getUserQuery($user, $userSearchParam->getQ())->paginate($paginate);
    }

    /**
     * @param string $email
     * @param User $user
     * @return bool
     * @throws \Exception
     * @deprecated
     */
    public function deleteUserByEmail(string $email, User $user): bool
    {
        $user = $this->getByEmail($email, $user);
        try {
            return $user->delete();
        } catch (QueryException $queryException) {
            report($queryException);
            return false;
        }
    }
}
