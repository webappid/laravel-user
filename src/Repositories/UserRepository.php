<?php

namespace WebAppId\User\Repositories;

use WebAppId\Lazy\Models\Join;
use WebAppId\User\Models\UserStatus;

/**
 * Class UserRepository
 * @package WebAppId\User\Http\Repositories
 */
class UserRepository
{
    use UserRepositoryTrait;

    public function __construct()
    {
        $user_statuses = app()->make(Join::class);
        $user_statuses->class = UserStatus::class;
        $user_statuses->foreign = 'status_id';
        $user_statuses->type = 'inner';
        $user_statuses->primary = 'user_statuses.id';
        $this->joinTable['user_statuses'] = $user_statuses;

    }
}
