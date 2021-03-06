<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Tests\Feature\Services;


use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\User\Services\PermissionService;
use WebAppId\User\Services\Requests\PermissionServiceRequest;
use WebAppId\User\Tests\TestCase;
use WebAppId\User\Tests\Unit\Repositories\PermissionRepositoryTest;

class PermissionServiceTest extends TestCase
{
    /**
     * @var PermissionService
     */
    protected $permissionService;

    /**
     * @var PermissionRepositoryTest
     */
    protected $permissionRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->permissionService = $this->container->make(PermissionService::class);
            $this->permissionRepositoryTest = $this->container->make(PermissionRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }

    }

    public function testGetById()
    {
        $contentServiceResponse = $this->testStore();
        $permissionServiceRequest = $this->getDummy();
        $result = $this->container->call([$this->permissionService, 'getById'], ['id' => $contentServiceResponse->permission->id, 'permissionServiceRequest' => $permissionServiceRequest]);
        self::assertTrue($result->status);
    }

    private function getDummy(int $number = 0): PermissionServiceRequest
    {
        $permissionRepositoryRequest = $this->container->call([$this->permissionRepositoryTest, 'getDummy'], ['no' => $number]);
        $permissionServiceRequest = null;
        try {
            $permissionServiceRequest = $this->container->make(PermissionServiceRequest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
        return Lazy::copy($permissionRepositoryRequest, $permissionServiceRequest);
    }

    public function testStore(int $number = 0)
    {
        $permissionServiceRequest = $this->getDummy($number);
        $result = $this->container->call([$this->permissionService, 'store'], ['permissionServiceRequest' => $permissionServiceRequest]);
        self::assertTrue($result->status);
        return $result;
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $result = $this->container->call([$this->permissionService, 'get']);
        self::assertTrue($result->status);
    }

    public function testUpdate()
    {
        $contentServiceResponse = $this->testStore();
        $permissionServiceRequest = $this->getDummy();
        $result = $this->container->call([$this->permissionService, 'update'], ['id' => $contentServiceResponse->permission->id, 'permissionServiceRequest' => $permissionServiceRequest]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $contentServiceResponse = $this->testStore();
        $result = $this->container->call([$this->permissionService, 'delete'], ['id' => $contentServiceResponse->permission->id]);
        self::assertTrue($result);
    }

    public function testGetWhere()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->permissionService, 'get'], ['q' => $q]);
        self::assertTrue($result->status);
    }

    public function testGetWhereCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->permissionService, 'getCount'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, $result);
    }
}
