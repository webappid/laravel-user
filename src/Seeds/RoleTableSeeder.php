<?php

namespace WebAppId\User\Seeds;

use Illuminate\Database\Seeder;
use WebAppId\User\Repositories\RoleRepository;
use WebAppId\User\Services\Params\RoleParam;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleRepository = $this->container->make(RoleRepository::class);
        
        $objRole = new RoleParam();
        $objRole->setName('admin');
        $objRole->setDescription('Role For Admin System');
        
        $result = $this->container->call([$roleRepository, 'getRoleByName'], ['name' => $objRole->getName()]);
       
        if ($result == null) {
            $this->container->call([$roleRepository, 'addRole'], ['request' => $objRole]);
        }
        
        $objRole = new RoleParam();
        $objRole->setName('member');
        $objRole->setDescription('Role For Member');
        
        $result = $this->container->call([$roleRepository, 'getRoleByName'], ['name' => $objRole->getName()]);
        if ($result == null) {
            $this->container->call([$roleRepository, 'addRole'], ['request' => $objRole]);
        }
    }
}
