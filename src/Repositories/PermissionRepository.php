<?php
/**
 * Created by PhpStorm.
 * User: Fadlika_N
 * Date: 3/5/2019
 * Time: 2:12 PM
 */

namespace WebAppId\User\Repositories;

use WebAppId\Lazy\Models\Join;
use WebAppId\User\Models\User;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 28/04/2020
 * Time: 22.39
 * Class PermissionRepository
 * @package WebAppId\User\Repositories
 */
class PermissionRepository
{
    use PermissionRepositoryTrait;

    public function __construct()
    {
        $users = app()->make(Join::class);
        $users->class = User::class;
        $users->foreign = 'created_by';
        $users->type = 'inner';
        $users->primary = 'users.id';
        $this->joinTable['users'] = $users;

        $updated_users = app()->make(Join::class);
        $updated_users->class = User::class;
        $updated_users->foreign = 'updated_by';
        $updated_users->type = 'inner';
        $updated_users->primary = 'updated_users.id';
        $this->joinTable['updated_users'] = $updated_users;
    }
}
