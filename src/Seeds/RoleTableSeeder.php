<?php

namespace WebAppId\User\Seeds;

use Illuminate\Database\Seeder;
use WebAppId\User\Repositories\RoleRepository;

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
        
        $objRole = new \StdClass();
        $objRole->name = 'admin';
        $objRole->description = 'Role For Admin Step Place Admin';
        
        $result = $this->container->call([$roleRepository, 'getRoleByName'], ['name' => $objRole->name]);
       
        if ($result == null) {
            $this->container->call([$roleRepository, 'addRole'], ['request' => $objRole]);
        }
        
        $objRole = new \StdClass();
        $objRole->name = 'member';
        $objRole->description = 'Role For Step Place Member';
        
        $result = $this->container->call([$roleRepository, 'getRoleByName'], ['name' => $objRole->name]);
        if ($result == null) {
            $this->container->call([$roleRepository, 'addRole'], ['request' => $objRole]);
        }
        
        $objRole = new \StdClass();
        $objRole->name = 'partner';
        $objRole->description = 'Role For Step Place Partner';
        
        $result = $this->container->call([$roleRepository, 'getRoleByName'], ['name' => $objRole->name]);
        if ($result == null) {
            $this->container->call([$roleRepository, 'addRole'], ['request' => $objRole]);
        }
    }
}
