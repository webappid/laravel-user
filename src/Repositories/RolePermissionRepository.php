<?php
/**
 * Created by PhpStorm.
 * User: Fadlika_N
 * Date: 3/6/2019
 * Time: 1:01 PM
 */

namespace WebAppId\User\Repositories;

use WebAppId\Lazy\Models\Join;
use WebAppId\User\Models\Permission;
use WebAppId\User\Models\User;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 28/04/2020
 * Time: 22.38
 * Class RolePermissionRepository
 * @package WebAppId\User\Repositories
 */
class RolePermissionRepository
{
    use RolePermissionRepositoryTrait;

    public function __construct()
    {
        $updated_users = app()->make(Join::class);
        $updated_users->class = User::class;
        $updated_users->foreign = 'updated_by';
        $updated_users->type = 'inner';
        $updated_users->primary = 'users.id';
        $this->joinTable['users'] = $updated_users;

        $created_users = app()->make(Join::class);
        $created_users->class = User::class;
        $created_users->foreign = 'created_by';
        $created_users->type = 'inner';
        $created_users->primary = 'created_users.id';
        $this->joinTable['created_users'] = $created_users;

        $permissions = app()->make(Join::class);
        $permissions->class = Permission::class;
        $permissions->foreign = 'permission_id';
        $permissions->type = 'inner';
        $permissions->primary = 'permissions.id';
        $this->joinTable['permissions'] = $permissions;

        $roles = app()->make(Join::class);
        $roles->class = Permission::class;
        $roles->foreign = 'role_id';
        $roles->type = 'inner';
        $roles->primary = 'roles.id';
        $this->joinTable['roles'] = $roles;
    }
}
