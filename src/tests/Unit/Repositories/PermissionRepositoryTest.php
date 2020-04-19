<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Tests\Unit\Repositories;

use WebAppId\User\Repositories\Requests\PermissionRepositoryRequest;
use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\User\Models\Permission;
use WebAppId\User\Repositories\PermissionRepository;
use WebAppId\User\Services\Params\PermissionParam;
use WebAppId\User\Tests\TestCase;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 19/04/20
 * Time: 10.40
 * Class PermissionRepositoryTest
 * @package WebAppId\User\Tests\Unit\Repositories
 */
class PermissionRepositoryTest extends TestCase
{
    /**
     * @var PermissionRepository
     */
    private $permissionRepository;

    /**
     * @var UserRepositoryTest
     */
    private $userRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->permissionRepository = $this->container->make(PermissionRepository::class);
            $this->userRepositoryTest = $this->container->make(UserRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function getDummy(int $no = 0): ?PermissionRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = $this->container->make(PermissionRepositoryRequest::class);
            $user = $this->container->call([$this->userRepositoryTest, 'testStore']);
            $dummy->name = $this->getFaker()->text(100);
            $dummy->description = $this->getFaker()->text(65535);
            $dummy->created_by = $user->id;
            $dummy->updated_by = $user->id;

        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $dummy;
    }

    public function testStore(int $no = 0): ?Permission
    {
        $permissionRepositoryRequest = $this->getDummy($no);
        $result = $this->container->call([$this->permissionRepository, 'store'], ['permissionRepositoryRequest' => $permissionRepositoryRequest]);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGetById()
    {
        $permission = $this->testStore();
        $result = $this->container->call([$this->permissionRepository, 'getById'], ['id' => $permission->id]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $permission = $this->testStore();
        $result = $this->container->call([$this->permissionRepository, 'delete'], ['id' => $permission->id]);
        self::assertTrue($result);
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }

        $resultList = $this->container->call([$this->permissionRepository, 'get']);
        self::assertGreaterThanOrEqual(1, count($resultList));
    }

    public function testUpdate()
    {
        $permission = $this->testStore();
        $permissionRepositoryRequest = $this->getDummy(1);
        $result = $this->container->call([$this->permissionRepository, 'update'], ['id' => $permission->id, 'permissionRepositoryRequest' => $permissionRepositoryRequest]);
        self::assertNotEquals(null, $result);
    }

    public function permissionRepository(): PermissionRepository
    {
        return $this->getContainer()->make(PermissionRepository::class);
    }

    /**
     * @return PermissionParam
     * @deprecated
     */
    public function getDummyData(): PermissionParam
    {
        $faker = $this->getFaker();
        $objPermission = new PermissionParam();
        $objPermission->setName($faker->name);
        $objPermission->setDescription($faker->text(190));
        return $objPermission;
    }

    /**
     * @param $dummy
     * @return Permission
     * @deprecated
     */
    public function createDummy($dummy): Permission
    {
        return $this->getContainer()->call([$this->permissionRepository(), 'add'], ['permissionParam' => $dummy]);
    }

    /**
     * @return Permission
     * @deprecated
     */
    public function testAddPermission(): Permission
    {
        $dummy = $this->getDummyData();
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

    /**
     * @deprecated
     */
    public function testGetAllPermission(): void
    {
        $result = $this->getContainer()->call([$this->permissionRepository(), 'getAll']);

        if (count($result) > 0) {
            self::assertTrue(true);
        } else {
            self::assertTrue(false);
        }
    }

    /**
     * @deprecated
     */
    public function testGetPermissionByName(): void
    {
        $result = $this->testAddPermission();

        $resultSearch = $this->getContainer()->call([$this->permissionRepository(), 'getByName'], ['name' => $result->name]);
        self::assertEquals($result->name, $resultSearch->name);
        self::assertEquals($result->description, $resultSearch->description);
    }

    /**
     * @deprecated
     */
    public function testGetPermissionById(): void
    {
        $result = $this->testAddPermission();

        $permissionResult = $this->getContainer()->call([$this->permissionRepository(), 'getDataById'], ['id' => $result->id]);
        $this->assertNotEquals(null, $permissionResult);
        self::assertEquals($result->name, $permissionResult->name);
        self::assertEquals($result->description, $permissionResult->description);
    }
}
