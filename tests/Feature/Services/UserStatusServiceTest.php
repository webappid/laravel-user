<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */


namespace WebAppId\User\Tests\Feature\Services;


use WebAppId\User\Services\Requests\UserStatusServiceRequest;
use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\User\Services\UserStatusService;
use WebAppId\User\Tests\TestCase;
use WebAppId\User\Tests\Unit\Repositories\UserStatusRepositoryTest;

class UserStatusServiceTest extends TestCase
{
    /**
     * @var UserStatusService
     */
    protected $userStatusService;

    /**
     * @var UserStatusRepositoryTest
     */
    protected $userStatusRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->userStatusService = $this->container->make(UserStatusService::class);
            $this->userStatusRepositoryTest = $this->container->make(UserStatusRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }

    }

    public function testGetById()
    {
        $contentServiceResponse = $this->testStore();
        $userStatusServiceRequest = $this->getDummy();
        $result = $this->container->call([$this->userStatusService, 'getById'], ['id' => $contentServiceResponse->userStatus->id, 'userStatusServiceRequest' => $userStatusServiceRequest]);
        self::assertTrue($result->status);
    }

    private function getDummy(int $number = 0): UserStatusServiceRequest
    {
        $userStatusRepositoryRequest = $this->container->call([$this->userStatusRepositoryTest, 'getDummy'], ['no' => $number]);
        $userStatusServiceRequest = null;
        try {
            $userStatusServiceRequest = $this->container->make(UserStatusServiceRequest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
        return Lazy::copy($userStatusRepositoryRequest, $userStatusServiceRequest);
    }

    public function testStore(int $number = 0)
    {
        $userStatusServiceRequest = $this->getDummy($number);
        $result = $this->container->call([$this->userStatusService, 'store'], ['userStatusServiceRequest' => $userStatusServiceRequest]);
        self::assertTrue($result->status);
        return $result;
    }

    public function testGet()
    {
        for ($i=0; $i<$this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++){
            $this->testStore($i);
        }
        $result = $this->container->call([$this->userStatusService, 'get']);
        self::assertTrue($result->status);
    }

    public function testUpdate()
    {
        $contentServiceResponse = $this->testStore();
        $userStatusServiceRequest = $this->getDummy();
        $result = $this->container->call([$this->userStatusService, 'update'], ['id' => $contentServiceResponse->userStatus->id, 'userStatusServiceRequest' => $userStatusServiceRequest]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $contentServiceResponse = $this->testStore();
        $result = $this->container->call([$this->userStatusService, 'delete'], ['id' => $contentServiceResponse->userStatus->id]);
        self::assertTrue($result);
    }

    public function testGetWhere()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->userStatusService, 'get'], ['q' => $q]);
        self::assertTrue($result->status);
    }

    public function testGetWhereCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->userStatusService, 'getCount'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, $result);
    }
}
