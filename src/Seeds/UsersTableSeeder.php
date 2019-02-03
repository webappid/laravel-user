<?php

namespace WebAppId\User\Seeds;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use WebAppId\User\Repositories\UserRepository;
use WebAppId\User\Repositories\UserRoleRepository;
use WebAppId\User\Services\Params\UserParam;
use WebAppId\User\Services\Params\UserRoleParam;

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
        $result = $this->container->call([$userRepository, 'getUserByEmail'], ['email' => 'root@noname.com']);
        
        if ($result == null) {
            $objUser = new UserParam();
            $objUser->setStatusId(2);
            $objUser->setName('user root system');
            $objUser->setEmail('root@noname.com');
            $objUser->setPassword($randomPassword);
            $objUser->provider = '';
            $result = $this->container->call([$userRepository, 'addUser'], ['request' => $objUser]);
            
            if ($result != null) {
                $userRole = $this->container->make(UserRoleRepository::class);
                $objUserRole = new UserRoleParam();
                $objUserRole->setUserId($result->id);
                $objUserRole->setRoleId(1);
                $result = $this->container->call([$userRole, 'addUserRole'], ['request' => $objUserRole]);
                if ($result != null) {
                    error_log('Default admin password : ' . $randomPassword . '. run php artisan db:seed --class=\'\WebAppId\User\Seeds\AdminResetPasswordTableSeeder\' to reset default pass root user');
                }
            }
        }
    }
}
