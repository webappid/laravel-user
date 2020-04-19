<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Tests\Unit\Repositories;

use WebAppId\User\Models\RolePermission;
use WebAppId\User\Repositories\Requests\RolePermissionRepositoryRequest;
use WebAppId\User\Repositories\RolePermissionRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\User\Tests\TestCase;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 19/04/20
 * Time: 11.18
 * Class RolePermissionRepositoryTest
 * @package WebAppId\User\Tests\Unit\Repositories
 */
class RolePermissionRepositoryTest extends TestCase
{
    /**
     * @var RolePermissionRepository
     */
    private $rolePermissionRepository;

    /**
     * @var RoleRepositoryTest
     */
    private $roleRepositoryTest;

    /**
     * @var PermissionRepositoryTest
     */
    private $permissionRepositoryTest;

    /**
     * @var UserRepositoryTest
     */
    private $userRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->rolePermissionRepository = $this->container->make(RolePermissionRepository::class);
            $this->roleRepositoryTest = $this->container->make(RoleRepositoryTest::class);
            $this->permissionRepositoryTest = $this->container->make(PermissionRepositoryTest::class);
            $this->userRepositoryTest = $this->container->make(UserRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function getDummy(int $no = 0): ?RolePermissionRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = $this->container->make(RolePermissionRepositoryRequest::class);
            $role = $this->container->call([$this->roleRepositoryTest, 'testStore']);
            $permission = $this->container->call([$this->permissionRepositoryTest, 'testStore']);
            $user = $this->container->call([$this->userRepositoryTest, 'testStore']);
            $dummy->role_id = $role->id;
            $dummy->permission_id = $permission->id;
            $dummy->created_by = $user->id;
            $dummy->updated_by = $user->id;

        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $dummy;
    }

    public function testStore(int $no = 0): ?RolePermission
    {
        $rolePermissionRepositoryRequest = $this->getDummy($no);
        $result = $this->container->call([$this->rolePermissionRepository, 'store'], ['rolePermissionRepositoryRequest' => $rolePermissionRepositoryRequest]);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGetById()
    {
        $rolePermission = $this->testStore();
        $result = $this->container->call([$this->rolePermissionRepository, 'getById'], ['id' => $rolePermission->id]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $rolePermission = $this->testStore();
        $result = $this->container->call([$this->rolePermissionRepository, 'delete'], ['id' => $rolePermission->id]);
        self::assertTrue($result);
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }

        $resultList = $this->container->call([$this->rolePermissionRepository, 'get']);
        self::assertGreaterThanOrEqual(1, count($resultList));
    }

    public function testUpdate()
    {
        $rolePermission = $this->testStore();
        $rolePermissionRepositoryRequest = $this->getDummy(1);
        $result = $this->container->call([$this->rolePermissionRepository, 'update'], ['id' => $rolePermission->id, 'rolePermissionRepositoryRequest' => $rolePermissionRepositoryRequest]);
        self::assertNotEquals(null, $result);
    }
}
