<?php

use Illuminate\Database\Seeder;
use WebAppId\User\UserRepository;
use Faker\Factory as Faker;

class AdminResetPasswordTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userRepository = $this->container->make(UserRepository::class);
        $result = $this->container->call([$userRepository, 'getUserByEmail'], ['email' => 'root@stepplace.com']);
        if ($result != null) {
            $randomPassword = Faker::create()->password;
            $result = $this->container->call([$userRepository, 'setUpdatePassword'], ['email' => $result->email, 'password' => $randomPassword]);
            if ($result != null) {
                error_log("Default admin password : " . $randomPassword);
            }
        }
    }
}
