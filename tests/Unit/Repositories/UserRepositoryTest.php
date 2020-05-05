<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */


namespace WebAppId\User\Tests\Unit\Repositories;

use WebAppId\User\Repositories\Requests\UserRepositoryRequest;
use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\User\Models\Activation;
use WebAppId\User\Models\User;
use WebAppId\User\Repositories\ActivationRepository;
use WebAppId\User\Repositories\RoleRepository;
use WebAppId\User\Repositories\UserRepository;
use WebAppId\User\Repositories\UserStatusRepository;
use WebAppId\User\Repositories\UserRoleRepository;
use WebAppId\User\Tests\TestCase;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 19/04/20
 * Time: 04.05
 * Class UserRepositoryTest
 * @package WebAppId\User\Tests\Unit\Repositories
 */
class UserRepositoryTest extends TestCase
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserStatusRepositoryTest
     */
    private $userStatusRepositoryTest;

    /**
     * @var UserStatusRepository
     */
    private $userStatusRepository;

    /**
     * @var ActivationRepository
     */
    private $activationRepository;

    /**
     * @var UserRoleRepository
     */
    private $userRoleRepository;

    /**
     * @var RoleRepository
     */
    private $roleRepository;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->userRepository = $this->container->make(UserRepository::class);
            $this->userStatusRepositoryTest = $this->container->make(UserStatusRepositoryTest::class);
            $this->userStatusRepository = $this->container->make(UserStatusRepository::class);
            $this->activationRepository = $this->container->make(ActivationRepository::class);
            $this->userRoleRepository = $this->container->make(UserRoleRepository::class);
            $this->roleRepository = $this->container->make(RoleRepository::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function getDummy(int $no = 0): ?UserRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = $this->container->make(UserRepositoryRequest::class);
            $userStatus = $this->container->call([$this->userStatusRepositoryTest, 'testStore']);
            $dummy->name = $this->getFaker()->text(255);
            $dummy->email = $this->getFaker()->text(255);
            $dummy->email_verified_at = $this->getFaker()->dateTime();
            $dummy->password = $this->getFaker()->text(255);
            $dummy->remember_token = $this->getFaker()->text(100);
            $dummy->status_id = $userStatus->id;

        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $dummy;
    }

    public function testStore(int $no = 0): ?User
    {
        $userRepositoryRequest = $this->getDummy($no);
        $result = $this->container->call([$this->userRepository, 'store'], ['userRepositoryRequest' => $userRepositoryRequest]);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGetById()
    {
        $user = $this->testStore();
        $result = $this->container->call([$this->userRepository, 'getById'], ['id' => $user->id]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $user = $this->testStore();
        $result = $this->container->call([$this->userRepository, 'delete'], ['id' => $user->id]);
        self::assertTrue($result);
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }

        $resultList = $this->container->call([$this->userRepository, 'get']);
        self::assertGreaterThanOrEqual(1, count($resultList));
    }

    public function testUpdate()
    {
        $user = $this->testStore();
        $userRepositoryRequest = $this->getDummy(1);
        $result = $this->container->call([$this->userRepository, 'update'], ['id' => $user->id, 'userRepositoryRequest' => $userRepositoryRequest]);
        self::assertNotEquals(null, $result);
    }

    public function testGetWhere()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->userRepository, 'get'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, count($result));
    }

    public function testGetWhereCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->userRepository, 'getCount'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, $result);
    }
}
