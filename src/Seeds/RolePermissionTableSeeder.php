<?php
/**
 * Created by PhpStorm.
 * User: Fadlika_N
 * Date: 3/6/2019
 * Time: 8:52 PM
 */

namespace WebAppId\User\Seeds;


use Illuminate\Database\Seeder;
use WebAppId\User\Models\RolePermission;
use WebAppId\User\Repositories\PermissionRepository;
use WebAppId\User\Repositories\RolePermissionRepository;
use WebAppId\User\Repositories\RoleRepository;
use WebAppId\User\Services\Params\PermissionParam;
use WebAppId\User\Services\Params\RolePermissionParam;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds;
     *
     * @return void
     */
    public function run()
    {
        // Add sample permission
        $permissionRepository = $this->container->make(PermissionRepository::class);

        $objPermission = new PermissionParam();
        $objPermission->setName('allaccess');
        $objPermission->setDescription('Permission All Access');

        $result = $this->container->call([$permissionRepository, 'getByName'], ['name' => $objPermission->getName()]);
        if ($result == null) {
            $resultPermission = $this->container->call([$permissionRepository, 'add'], ['permissionParam' => $objPermission]);
        } else {
            $resultPermission = null;
        }

        // Set Permission to existing Role
        $roleRepository = $this->container->make(RoleRepository::class);
        $rolePermissionRepository = $this->container->make(RolePermissionRepository::class);
        $role = $this->container->call([$roleRepository, 'getRoleById'], ['id' => 1]);
        if ($role != null && $resultPermission != null) {
            $objRolePermission = new RolePermissionParam();
            $objRolePermission->setRoleId($role->id);
            $objRolePermission->setPermissionId($resultPermission->id);

            $rolePermission = $this->container->call([$rolePermissionRepository, 'add'], ['rolePermissionParam' => $objRolePermission]);
            if ($rolePermission != null) {
                error_log('Default Role Permission is already set');
            }
        }

    }
}
