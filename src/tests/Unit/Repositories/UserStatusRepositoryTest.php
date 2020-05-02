<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Tests\Unit\Repositories;

use WebAppId\User\Repositories\Requests\UserStatusRepositoryRequest;
use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\User\Models\UserStatus;
use WebAppId\User\Repositories\UserStatusRepository;
use WebAppId\Tests\TestCase;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 19/04/20
 * Time: 10.39
 * Class UserStatusRepositoryTest
 * @package WebAppId\Tests\Unit\Repositories
 */
class UserStatusRepositoryTest extends TestCase
{
    /**
     * @var UserStatusRepository 
     */
    private $userStatusRepository;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->userStatusRepository = $this->container->make(UserStatusRepository::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function getDummy(int $no = 0): ?UserStatusRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = $this->container->make(UserStatusRepositoryRequest::class);
            $dummy->name = $this->getFaker()->text(255);

        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $dummy;
    }

    public function testStore(int $no = 0): ?UserStatus
    {
        $userStatusRepositoryRequest = $this->getDummy($no);
        $result = $this->container->call([$this->userStatusRepository, 'store'], ['userStatusRepositoryRequest' => $userStatusRepositoryRequest]);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGetById()
    {
        $userStatus = $this->testStore();
        $result = $this->container->call([$this->userStatusRepository, 'getById'], ['id' => $userStatus->id]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $userStatus = $this->testStore();
        $result = $this->container->call([$this->userStatusRepository, 'delete'], ['id' => $userStatus->id]);
        self::assertTrue($result);
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }

        $resultList = $this->container->call([$this->userStatusRepository, 'get']);
        self::assertGreaterThanOrEqual(1, count($resultList));
    }

    public function testUpdate()
    {
        $userStatus = $this->testStore();
        $userStatusRepositoryRequest = $this->getDummy(1);
        $result = $this->container->call([$this->userStatusRepository, 'update'], ['id' => $userStatus->id, 'userStatusRepositoryRequest' => $userStatusRepositoryRequest]);
        self::assertNotEquals(null, $result);
    }
}
