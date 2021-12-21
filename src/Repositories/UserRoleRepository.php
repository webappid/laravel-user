<?php
/**
 * Created by PhpStorm.
 * Users: dyangalih
 * Date: 05/11/18
 * Time: 01.58
 */

namespace WebAppId\User\Repositories;


use WebAppId\Lazy\Models\Join;
use WebAppId\User\Models\Role;
use WebAppId\User\Models\User;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 19/04/20
 * Time: 04.36
 * Class UserRoleRepository
 * @package WebAppId\User\Repositories
 */
class UserRoleRepository
{
    use UserRoleRepositoryTrait;

    public function __construct()
    {
        $users = app()->make(Join::class);
        $users->class = User::class;
        $users->foreign = 'user_id';
        $users->type = 'inner';
        $users->primary = 'users.id';
        $this->joinTable['users'] = $users;

        $roles = app()->make(Join::class);
        $roles->class = Role::class;
        $roles->foreign = 'role_id';
        $roles->type = 'inner';
        $roles->primary = 'roles.id';
        $this->joinTable['roles'] = $roles;
    }
}
