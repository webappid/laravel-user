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
     * @param UserRepository $userRepository
     * @return void
     */
    public function run(UserRepository $userRepository)
    {
        $result = $this->container->call([$userRepository, 'getByEmail'], ['email' => 'root@noname.com']);
        if ($result != null) {
            $randomPassword = Faker::create()->password;
            $result = $this->container->call([$userRepository, 'setUpdatePassword'], ['email' => $result->email, 'password' => $randomPassword]);
            if ($result != null) {
                error_log("Default admin password : " . $randomPassword);
            }
        }
    }
}
