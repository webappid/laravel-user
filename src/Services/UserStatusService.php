<?php

namespace WebAppId\User\Services;

use Illuminate\Container\Container;
use WebAppId\User\Repositories\UserStatusRepository;

/**
 * Class UserStatusService
 * @package App\Http\Services
 */
class UserStatusService
{
    private $container;
    
    /**
     * UserStatusService constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    /**
     * @param UserStatusRepository $userStatusRepository
     * @return mixed
     */
    public function getAll(UserStatusRepository $userStatusRepository){
        return $this->container->call([$userStatusRepository, 'getAll']);
    }

}