<?php

namespace WebAppId\User\Services;

use Illuminate\Container\Container;
use WebAppId\User\Repositories\UserRepository;
use WebAppId\User\Repositories\UserRoleRepository;

/**
 * Class UserService
 * @package App\Http\Services
 */
class UserService
{
    private $container;
    
    /**
     * UserService constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    public function addUser($request, UserRepository $userRepository, UserRoleRepository $userRoleRepository)
    {
        $resultUser = $this->container->call([$userRepository, 'addUser'], ['request' => $request]);
        if ($resultUser == null) {
            return null;
        } else {
            $objUserRole = new \StdClass();
            $objUserRole->user_id = $resultUser->id;
            $objUserRole->role_id = $request->role_id;
            
            $resultUserRole = $this->container->call([$userRoleRepository, 'addUserRole'], ['request' => $objUserRole]);
            if ($resultUserRole == null) {
                return null;
            } else {
                $result['user'] = $resultUser;
                $result['roles'] = $resultUser->roles;
                
                return $result;
            }
        }
    }
}