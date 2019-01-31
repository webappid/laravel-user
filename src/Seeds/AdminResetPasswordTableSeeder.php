<?php
namespace WebAppId\User\Seeds;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use WebAppId\User\Repositories\UserRepository;


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
        $result = $this->container->call([$userRepository, 'getUserByEmail'], ['email' => 'root@noname.com']);
        if ($result != null) {
            $randomPassword = Faker::create()->password;
            $result = $this->container->call([$userRepository, 'setUpdatePassword'], ['email' => $result->email, 'password' => $randomPassword]);
            if ($result != null) {
                error_log("Default admin password : " . $randomPassword);
            }
        }
    }
}
