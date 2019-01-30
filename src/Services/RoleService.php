<?php
/**
 * Created by PhpStorm.
 * Users: dyangalih
 * Date: 05/11/18
 * Time: 16.43
 */

namespace WebAppId\User\Services;


use Illuminate\Container\Container;
use WebAppId\User\Repositories\RoleRepository;
use WebAppId\User\Response\GetRoleResponse;

/**
 * Class RoleService
 * @package App\Http\Services
 */
class RoleService
{
    private $container;
    
    /**
     * RoleService constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    public function getAllRole(RoleRepository $roleRepository): ?object
    {
        return $this->container->call([$roleRepository, 'getAllRole']);
    }
}