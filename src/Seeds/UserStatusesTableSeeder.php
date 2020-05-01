<?php

namespace WebAppId\User\Seeds;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Seeder;
use WebAppId\User\Repositories\Requests\UserStatusRepositoryRequest;
use WebAppId\User\Repositories\UserStatusRepository;

class UserStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param UserStatusRepository $userStatusRepository
     * @return void
     * @throws BindingResolutionException
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
