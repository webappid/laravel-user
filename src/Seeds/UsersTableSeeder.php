<?php

namespace WebAppId\User\Seeds;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use WebAppId\User\Repositories\Requests\UserRepositoryRequest;
use WebAppId\User\Repositories\Requests\UserRoleRepositoryRequest;
use WebAppId\User\Repositories\UserRepository;
use WebAppId\User\Repositories\UserRoleRepository;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param UserRepository $userRepository
     * @param UserRoleRepository $userRoleRepository
     * @return void
     * @throws BindingResolutionException
     */
    public function run(UserRepository $userRepository, UserRoleRepository $userRoleRepository)
    {

        $randomPassword = Str::random(8);
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
