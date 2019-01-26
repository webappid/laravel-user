<?php

namespace WebAppId\User\Seeds;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use WebAppId\User\Repositories\UserRepository;
use WebAppId\User\Repositories\UserRoleRepository;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $randomPassword = Faker::create()->password;
        $userRepository = $this->container->make(UserRepository::class);
        $result = $this->container->call([$userRepository, 'getUserByEmail'], ['email' => 'root@stepplace.com']);
        
        if ($result == null) {
            $objUser = new \StdClass;
            $objUser->status_id = '2';
            $objUser->name = 'user root step place';
            $objUser->email = 'root@stepplace.com';
            $objUser->password = $randomPassword;
            $objUser->provider = '';
            $result = $this->container->call([$userRepository, 'addUser'], ['request' => $objUser]);
            
            if ($result != null) {
                $userRole = $this->container->make(UserRoleRepository::class);
                $objUserRole = new \StdClass();
                $objUserRole->user_id = $result->id;
                $objUserRole->role_id = '1';
                $result = $this->container->call([$userRole, 'addUserRole'], ['request' => $objUserRole]);
                if ($result != null) {
                    error_log("Default admin password : " . $randomPassword);
                }
            }
        }
    }
}
