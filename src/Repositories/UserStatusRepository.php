<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */


namespace WebAppId\User\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\User\Models\UserStatus;
use WebAppId\User\Repositories\Contracts\UserStatusRepositoryContract;
use WebAppId\User\Repositories\Requests\UserStatusRepositoryRequest;


/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 18/04/20
 * Time: 22.07
 * Class UserStatusRepository
 * @package WebAppId\User\Repositories
 */
class UserStatusRepository implements UserStatusRepositoryContract
{
    use UserStatusRepositoryTrait;
}
