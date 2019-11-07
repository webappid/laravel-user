<?php
/**
 * Created by PhpStorm.
 * User: Fadlika_N
 * Date: 3/8/2019
 * Time: 9:29 AM
 */

namespace WebAppId\User\Tests\Unit\Repositories;


use WebAppId\User\Models\Permission;
use WebAppId\User\Repositories\PermissionRepository;
use WebAppId\User\Services\Params\PermissionParam;
use WebAppId\User\Tests\TestCase;

class PermissionRepositoryTest extends TestCase
{
    public function permissionRepository(): PermissionRepository
    {
        return $this->getContainer()->make(PermissionRepository::class);
    }

    public function getDummy(): PermissionParam
    {
        $faker = $this->getFaker();
        $objPermission = new PermissionParam();
        $objPermission->setName($faker->name);
        $objPermission->setDescription($faker->text(190));
        return $objPermission;
    }

    public function createDummy($dummy): Permission
    {
        return $this->getContainer()->call([$this->permissionRepository(), 'add'], ['permissionParam' => $dummy]);
    }

    public function testAddPermission(): Permission
    {
        $dummy = $this->getDummy();
        $result = $this->createDummy($dummy);
        if ($result == null) {
            self::assertTrue(false);
        } else {
            self::assertTrue(true);
            self::assertEquals($dummy->getName(), $result->name);
            self::assertEquals($dummy->getDescription(), $result->description);
        }
        return $result;
    }

    public function testGetAllPermission(): void
    {
        $result = $this->getContainer()->call([$this->permissionRepository(), 'getAll']);

        if (count($result) > 0) {
            self::assertTrue(true);
        } else {
            self::assertTrue(false);
        }
    }

    public function testGetPermissionByName(): void
    {
        $result = $this->testAddPermission();

        $resultSearch = $this->getContainer()->call([$this->permissionRepository(), 'getByName'], ['name' => $result->name]);
        self::assertEquals($result->name, $resultSearch->name);
        self::assertEquals($result->description, $resultSearch->description);
    }

    public function testGetPermissionById(): void
    {
        $result = $this->testAddPermission();

        $permissionResult = $this->getContainer()->call([$this->permissionRepository(), 'getById'], ['id' => $result->id]);
        $this->assertNotEquals(null, $permissionResult);
        self::assertEquals($result->name, $permissionResult->name);
        self::assertEquals($result->description, $permissionResult->description);
    }
}