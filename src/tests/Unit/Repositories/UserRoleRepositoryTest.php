<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\Tests\Unit\Repositories;

use WebAppId\User\Models\UserRole;
use WebAppId\User\Repositories\Requests\UserRoleRepositoryRequest;
use WebAppId\User\Repositories\UserRoleRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\Tests\TestCase;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 19/04/20
 * Time: 10.39
 * Class UserRoleRepositoryTest
 * @package WebAppId\Tests\Unit\Repositories
 */
class UserRoleRepositoryTest extends TestCase
{

    /**
     * @var UserRoleRepository
     */
    private $userRoleRepository;

    private $userRepositoryTest;

    private $roleRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->userRoleRepository = $this->container->make(UserRoleRepository::class);
            $this->userRepositoryTest = $this->container->make(UserRepositoryTest::class);
            $this->roleRepositoryTest = $this->container->make(RoleRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function getDummy(int $no = 0): ?UserRoleRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = $this->container->make(UserRoleRepositoryRequest::class);
            $user = $this->container->call([$this->userRepositoryTest, 'testStore']);
            $role = $this->container->call([$this->roleRepositoryTest, 'testStore']);
            $dummy->user_id = $user->id;
            $dummy->role_id = $role->id;

        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $dummy;
    }

    public function testStore(int $no = 0): ?UserRole
    {
        $userRoleRepositoryRequest = $this->getDummy($no);
        $result = $this->container->call([$this->userRoleRepository, 'store'], ['userRoleRepositoryRequest' => $userRoleRepositoryRequest]);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGetById()
    {
        $userRole = $this->testStore();
        $result = $this->container->call([$this->userRoleRepository, 'getById'], ['id' => $userRole->id]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $userRole = $this->testStore();
        $result = $this->container->call([$this->userRoleRepository, 'delete'], ['id' => $userRole->id]);
        self::assertTrue($result);
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }

        $resultList = $this->container->call([$this->userRoleRepository, 'get']);
        self::assertGreaterThanOrEqual(1, count($resultList));
    }

    public function testUpdate()
    {
        $userRole = $this->testStore();
        $userRoleRepositoryRequest = $this->getDummy(1);
        $result = $this->container->call([$this->userRoleRepository, 'update'], ['id' => $userRole->id, 'userRoleRepositoryRequest' => $userRoleRepositoryRequest]);
        self::assertNotEquals(null, $result);
    }
}
