<?php

namespace WebAppId\User\Seeds;

use Illuminate\Database\Seeder;
use WebAppId\User\Repositories\UserStatusRepository;
use WebAppId\User\Services\Params\UserStatusParam;

class UserStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        
        $userStatusRepository = $this->container->make(UserStatusRepository::class);
        
        $statuses = array(
            'Non Active',
            'Active',
            'Disable',
            'Block'
        );
        
        foreach ($statuses as $status) {
            $userStatusData = new UserStatusParam();
            $userStatusData->setName($status);
            $result = $this->container->call([$userStatusRepository, 'getByName'], ['name' => $status]);
            if ($result == null) {
                $this->container->call([$userStatusRepository, 'addUserStatus'], ['userStatusParam' => $userStatusData]);
            }
        }
        
    }
}
