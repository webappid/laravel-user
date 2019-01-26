<?php

namespace WebAppId\User\Seeds;

use Illuminate\Database\Seeder;

/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-25
 * Time: 11:55
 */

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UserStatusesTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(UserStatusesTableSeeder::class);
    }
    
}