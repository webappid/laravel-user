<?php
/**
 * Created by PhpStorm.
 * Users: dyangalih
 * Date: 05/11/18
 * Time: 16.43
 */

namespace WebAppId\User\Service;


use Illuminate\Container\Container;
use WebAppId\User\Repositories\RoleRepository;

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
    
    public function getAllRole(RoleRepository $roleRepository){
        return $this->container->call([$roleRepository, 'getAllRole']);
    }
}