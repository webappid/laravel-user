<?php

namespace WebAppId\User\Seeds;

use Illuminate\Database\Seeder;
use WebAppId\User\Repositories\Requests\RoleRepositoryRequest;
use WebAppId\User\Repositories\RoleRepository;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(RoleRepository $roleRepository)
    {
        $roleList = [
            [
                "name" => "admin",
                "description" => "Role For Admin System"
            ],
            [
                "name" => "member",
                "description" => "Role For Member"
            ],
            [
                "name" => "partner",
                "description" => "Role For Partner"
            ],

        ];

        foreach ($roleList as $role) {
            $roleRepositoryRequest = $this->container->make(RoleRepositoryRequest::class);
            $roleRepositoryRequest->name = $role["name"];
            $roleRepositoryRequest->description = $role["description"];
            $find = $this->container->call([$roleRepository, 'getByName'], ['name' => $roleRepositoryRequest->name]);
            if ($find == null) {
                $this->container->call([$roleRepository, 'store'], compact('roleRepositoryRequest'));
            }
        }
    }
}
