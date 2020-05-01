<?php
/**
 * Created by PhpStorm.
 * User: Fadlika_N
 * Date: 3/6/2019
 * Time: 8:52 PM
 */

namespace WebAppId\User\Seeds;


use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Seeder;
use WebAppId\User\Repositories\PermissionRepository;
use WebAppId\User\Repositories\Requests\PermissionRepositoryRequest;
use WebAppId\User\Repositories\Requests\RolePermissionRepositoryRequest;
use WebAppId\User\Repositories\RolePermissionRepository;
use WebAppId\User\Repositories\RoleRepository;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds;
     *
     * @param PermissionRepository $permissionRepository
     * @param RoleRepository $roleRepository
     * @param RolePermissionRepository $rolePermissionRepository
     * @return void
     * @throws BindingResolutionException
     */
    public function run(PermissionRepository $permissionRepository, RoleRepository $roleRepository, RolePermissionRepository $rolePermissionRepository)
    {
        $permissionRepositoryRequest = $this->container->make(PermissionRepositoryRequest::class);
        $permissionRepositoryRequest->name = 'allaccess';
        $permissionRepositoryRequest->description = 'Permission All Access';
        $permissionRepositoryRequest->created_by = 1;
        $permissionRepositoryRequest->updated_by = 1;

        $result = $this->container->call([$permissionRepository, 'getByName'], ['name' => $permissionRepositoryRequest->name]);
        if ($result == null) {
            $resultPermission = $this->container->call([$permissionRepository, 'store'], compact('permissionRepositoryRequest'));
            $role = $this->container->call([$roleRepository, 'getById'], ['id' => 1]);
            $rolePermissionRepositoryRequest = $this->container->make(RolePermissionRepositoryRequest::class);
            $rolePermissionRepositoryRequest->role_id = $role->id;
            $rolePermissionRepositoryRequest->permission_id = $resultPermission->id;
            $rolePermission = $this->container->call([$rolePermissionRepository, 'store'], compact('rolePermissionRepositoryRequest'));
            if ($rolePermission != null) {
                error_log('Default Role Permission is already set');
            }
        }
    }
}
