<?php
/**
 * Created by PhpStorm.
 * User: Fadlika_N
 * Date: 3/8/2019
 * Time: 9:26 AM
 */

namespace WebAppId\User\Services;

use Illuminate\Container\Container;
use WebAppId\User\Repositories\PermissionRepository;

/**
 * Class PermissionService
 * @package WebAppId\User\Services
 */
class PermissionService
{
    private $container;

    /**
     * PermissionService constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getAllPermission(PermissionRepository $permissionRepository): ?object
    {
        return $this->container->call([$permissionRepository, 'getAll']);
    }
}