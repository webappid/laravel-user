<?php

namespace WebAppId\User\Seeds;

use Illuminate\Database\Seeder;
use WebAppId\User\Repositories\Requests\UserStatusRepositoryRequest;
use WebAppId\User\Repositories\UserStatusRepository;
use WebAppId\User\Services\Params\UserStatusParam;

class UserStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(UserStatusRepository $userStatusRepository)
    {
        $statuses = array(
            'Non Active',
            'Active',
            'Disable',
            'Block'
        );
        
        foreach ($statuses as $status) {
            $userStatusRepositoryRequest = $this->container->make(UserStatusRepositoryRequest::class);
            $userStatusRepositoryRequest->name = $status;
            $result = $this->container->call([$userStatusRepository, 'getByName'], ['name' => $status]);
            if ($result == null) {
                $this->container->call([$userStatusRepository, 'store'], compact('userStatusRepositoryRequest'));
            }
        }
    }
}
