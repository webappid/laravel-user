<?php

namespace WebAppId\User\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use WebAppId\User\Repositories\UserRepository;


class AdminResetPasswordTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param UserRepository $userRepository
     * @return void
     */
    public function run(UserRepository $userRepository)
    {
        $result = $this->container->call([$userRepository, 'getByEmail'], ['email' => 'root@noname.com']);
        if ($result != null) {
            $randomPassword = $randomPassword = Str::random(8);
            $result = $this->container->call([$userRepository, 'setUpdatePassword'], ['email' => $result->email, 'password' => $randomPassword]);
            if ($result != null) {
                error_log("Default admin password : " . $randomPassword);
            }
        }
    }
}
