<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\User\Repositories;


use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use WebAppId\Lazy\Tools\Lazy;
use WebAppId\Lazy\Traits\RepositoryTrait;
use WebAppId\User\Models\User;
use WebAppId\User\Repositories\Requests\UserRepositoryRequest;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20/09/2020
 * Time: 19.17
 * Class UserRepositoryTrait
 * @package WebAppId\User\Repositories
 */
trait UserRepositoryTrait
{
    use RepositoryTrait;

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
        $user = $user->find($id);

        if ($user != null) {
            try {
                $userRepositoryRequest->remember_token = $user->remember_token;
                if ($userRepositoryRequest->password == null) {
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
        return $this
            ->getJoin($user)
            ->find($id, $this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, User $user): bool
    {
        $user = $user->find($id);
        if ($user != null) {
            return $user->delete();
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(User $user, int $length = 12, string $q = null): LengthAwarePaginator
    {
        return $this->getJoin($user)
            ->when($q != null, function ($query) use ($q) {
                return $this->getFilter($query, $q);
            })
            ->paginate($length, $this->getColumn())
            ->appends(request()->input());
    }

    /**
     * @param Builder $query
     * @param string $q
     * @return Builder
     */
    protected function getFilter(Builder $query, string $q): Builder
    {
        return $query
            ->where('users.name', 'LIKE', '%' . $q . '%')
            ->orWhere('users.email', $q);
    }

    /**
     * @inheritDoc
     */
    public function getCount(User $user, string $q = null): int
    {
        return $this->getJoin($user)
            ->when($q != null, function ($query) use ($q) {
                return $this->getFilter($query, $q);
            })->count();
    }

    /**
     * @inheritDoc
     */
    public function getByEmail(string $email, User $user): ?User
    {
        return $this->getJoin($user)
            ->where('email', $email)
            ->first($this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function setUpdatePassword(string $email, string $password, User $user): ?User
    {
        $user = $user->where('email', $email)->first();
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
        $user = $user->where('email', $email)->first();
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
        $user = $user->where('email', $email)->first();
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
        $user = $user->where('email', $email)->first();
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
    public function setResetPasswordTokenByEmail(string $email, Application $application, User $user): ?string
    {
        $user = $user->where('email', $email)->first();

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

        $token = null;

        if ($user != null) {
            $token = $tokens->createNewToken();
            Cache::put($token, $user->email, $config['expire']);
            $user->sendPasswordResetNotification($token);
        }

        return $token;
    }

    /**
     * @inheritDoc
     */
    public function updateRememberToken(int $userId, User $user, bool $revoke = false): User
    {
        if ($revoke) {
            $token = null;
        } else {
            $token = Str::random(80);
        }

        $user = $user->find($userId);
        if ($user != null) {
            try {
                $user->api_token = $token;
                $user->save();
            } catch (QueryException $queryException) {
                report($queryException);
            }
        }
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function getLoginToken(string $email, User $user): string
    {
        $user = $user->where('email', $email)->first();
        $token = null;
        if ($user != null) {
            $token = Uuid::uuid4()->toString();
            Cache::put($token, $user);
        }

        return $token;
    }

    /**
     * @inheritDoc
     */
    public function getUserByLoginToken(string $token, User $user): user
    {
        return Cache::pull($token);
    }
}