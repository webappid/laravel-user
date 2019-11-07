<?php
/**
 * Created by PhpStorm.
 * User: Fadlika_N
 * Date: 3/8/2019
 * Time: 9:46 AM
 */

namespace WebAppId\User\Tests\Feature\Services;


use WebAppId\User\Services\PermissionService;
use WebAppId\User\Tests\TestCase;

class PermissionServiceTest extends TestCase
{

    protected function permissionService()
    {
        return $this->getContainer()->make(PermissionService::class);
    }

    public function testGetAllPermission()
    {
        $result = $this->getContainer()->call([$this->permissionService(), 'getAllPermission']);
        if ($result == null) {
            self::assertTrue(false);
        } else {
            self::assertTrue(true);
        }
    }
}