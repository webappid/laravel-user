<?php
/**
 * Created by PhpStorm.
 * UserParam: dyangalih
 * Date: 2019-01-26
 * Time: 13:17
 */

namespace WebAppId\User\Tests\Feature\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\User\Services\Requests\RoleServiceRequest;
use WebAppId\User\Services\RoleService;
use WebAppId\User\Tests\TestCase;
use WebAppId\User\Tests\Unit\Repositories\RoleRepositoryTest;

class RoleServiceTest extends TestCase
{
    /**
     * @var RoleService
     */
    protected $roleService;

    /**
     * @var RoleRepositoryTest
     */
    protected $roleRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->roleService = $this->container->make(RoleService::class);
            $this->roleRepositoryTest = $this->container->make(RoleRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }

    }

    public function testGetById()
    {
        $contentServiceResponse = $this->testStore();
        $roleServiceRequest = $this->getDummy();
        $result = $this->container->call([$this->roleService, 'getById'], ['id' => $contentServiceResponse->role->id, 'roleServiceRequest' => $roleServiceRequest]);
        self::assertTrue($result->status);
    }

    private function getDummy(int $number = 0): RoleServiceRequest
    {
        $roleRepositoryRequest = $this->container->call([$this->roleRepositoryTest, 'getDummyData'], ['no' => $number]);
        $roleServiceRequest = null;
        try {
            $roleServiceRequest = $this->container->make(RoleServiceRequest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
        return Lazy::copy($roleRepositoryRequest, $roleServiceRequest);
    }

    public function testStore(int $number = 0)
    {
        $roleServiceRequest = $this->getDummy($number);
        $result = $this->container->call([$this->roleService, 'store'], compact('roleServiceRequest'));
        self::assertTrue($result->status);
        return $result;
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $result = $this->container->call([$this->roleService, 'get']);
        self::assertTrue($result->status);
    }

    public function testUpdate()
    {
        $contentServiceResponse = $this->testStore();
        $roleServiceRequest = $this->getDummy();
        $result = $this->container->call([$this->roleService, 'update'], ['id' => $contentServiceResponse->role->id, 'roleServiceRequest' => $roleServiceRequest]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $contentServiceResponse = $this->testStore();
        $result = $this->container->call([$this->roleService, 'delete'], ['id' => $contentServiceResponse->role->id]);
        self::assertTrue($result);
    }

    public function testGetWhere()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->roleService, 'get'], ['q' => $q]);
        self::assertTrue($result->status);
    }

    public function testGetWhereCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->roleService, 'getCount'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, $result);
    }
}
