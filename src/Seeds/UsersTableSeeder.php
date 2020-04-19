<?php

namespace WebAppId\User\Seeds;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use WebAppId\User\Repositories\Requests\UserRepositoryRequest;
use WebAppId\User\Repositories\Requests\UserRoleRepositoryRequest;
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
    public function run(UserRepository $userRepository, UserRoleRepository $userRoleRepository)
    {
        $randomPassword = Faker::create()->password;
        $result = $this->container->call([$userRepository, 'getByEmail'], ['email' => 'root@noname.com']);

        if ($result == null) {
            $userRepositoryRequest = $this->container->make(UserRepositoryRequest::class);
            $userRepositoryRequest->status_id = 2;
            $userRepositoryRequest->name = "user root system";
            $userRepositoryRequest->email = "root@noname.com";
            $userRepositoryRequest->password = $randomPassword;
            $result = $this->container->call([$userRepository, 'store'], compact('userRepositoryRequest'));

            if ($result != null) {
                $userRoleRepositoryRequest = $this->container->make(UserRoleRepositoryRequest::class);
                $userRoleRepositoryRequest->user_id = $result->id;
                $userRoleRepositoryRequest->role_id = 1;
                $result = $this->container->call([$userRoleRepository, 'store'], compact('userRoleRepositoryRequest'));
                if ($result != null) {
                    error_log('Default admin password : ' . $randomPassword . '. run php artisan db:seed --class=\'\WebAppId\User\Seeds\AdminResetPasswordTableSeeder\' to reset default pass root user');
                }
            }
        }
    }
}
