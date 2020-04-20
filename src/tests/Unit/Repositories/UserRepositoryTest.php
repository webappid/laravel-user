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
use WebAppId\User\Services\Params\UserParam;
use WebAppId\User\Services\Params\UserRoleParam;
use WebAppId\User\Services\Params\UserSearchParam;
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
        $result = $this->container->call([$this->userRepository, 'getWhere'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, count($result));
    }

    public function testGetWhereCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->userRepository, 'getWhereCount'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, $result);
    }

    /**
     * @return UserParam|null
     * @deprecated
     */
    public function getDummyUser(): ?UserParam
    {
        $objUser = new UserParam();

        $objUser->setName($this->getFaker()->name);
        $objUser->setEmail($this->getFaker()->safeEmail);
        $objUser->setStatusId(1);
        $objUser->setPassword($this->getFaker()->password);
        return $objUser;
    }

    /**
     * @param $dummy
     * @return User|null
     * @deprecated
     */
    public function createDummy($dummy): ?User
    {
        return $this->getContainer()->call([$this->userRepository, 'addUser'], ['request' => $dummy]);
    }

    /**
     * @param int $userId
     * @return Activation|null
     * @deprecated
     */
    public function setActivation(int $userId): ?Activation
    {
        return $this->getContainer()->call([$this->activationRepository, 'store'], ['userId' => $userId]);
    }

    /**
     * @return User|null
     * @deprecated
     */
    public function testAddUser(): ?User
    {
        $dummy = $this->getDummyUser();
        $result = $this->createDummy($dummy);
        $resultFailed = $this->createDummy($dummy);
        self::assertEquals(null, $resultFailed);

        $resultStatus = $this->getContainer()->call([$this->userStatusRepository, 'getStatusById'], ['id' => $dummy->getStatusId()]);

        if ($result != null) {

            $objUserRole = new UserRoleParam();
            $objUserRole->setUserId($result->id);
            $objUserRole->setRoleId($this->getFaker()->numberBetween(1, 2));

            $resultUserRole = $this->getContainer()->call([$this->userRoleRepository, 'addUserRole'], ['request' => $objUserRole]);

            if ($resultUserRole == null) {
                self::assertTrue(false);
            } else {
                self::assertTrue(true);
                self::assertEquals($objUserRole->getUserId(), $resultUserRole->user_id);
                self::assertEquals($objUserRole->getRoleId(), $resultUserRole->role_id);

                $roleResult = $this->getContainer()->call([$this->roleRepository, 'getRoleById'], ['id' => $objUserRole->getRoleId()]);
                self::assertNotEquals(null, $roleResult);
                self::assertEquals($result->roles[0]->name, $roleResult->name);
            }

            $activationResult = $this->setActivation($result->id);
            self::assertNotEquals(null, $activationResult);

            $this->assertTrue(true);
            $this->assertEquals($dummy->getStatusId(), $result->status_id);
            $this->assertEquals($resultStatus->name, $result->status->name);

            return $result;
        } else {
            $this->assertTrue(false);
            return null;
        }
    }

    /**
     * @deprecated
     */
    public function testGetUserByEmail(): void
    {
        $result = $this->createDummy($this->getDummyUser());

        if ($result != null) {
            $result = $this->getContainer()->call([$this->userRepository, 'getUserByEmail'], ['email' => $result->email]);
            self::assertNotEquals(null, $result);
        }
    }

    /**
     * @deprecated
     */
    public function testUpdateUserPassword(): void
    {
        $result = $this->testStore();
        if ($result != null) {
            $result = $this->getContainer()->call([$this->userRepository, 'getUserByEmail'], ['email' => $result->email]);
            if ($result != null) {
                $result->password = $this->getFaker()->password;
                $resultUpdate = $this->getContainer()->call([$this->userRepository, 'setUpdatePassword'], ['email' => $result->email, 'password' => $result->password]);
                self::assertNotEquals(null, $resultUpdate);
            } else {
                $this->assertTrue(false);
            }
        }
    }

    /**
     * @deprecated
     */
    public function testUpdateUserStatus(): void
    {
        $result = $this->testStore();
        if ($result != null) {
            $result = $this->getContainer()->call([$this->userRepository, 'getUserByEmail'], ['email' => $result->email]);
            if ($result != null) {
                $result->status_id = $this->getFaker()->numberBetween(1, 4);
                $resultFailed = $this->getContainer()->call([$this->userRepository, 'setUpdateStatusUser'], ['email' => $this->getFaker()->safeEmail, 'status' => $result->status_id]);
                self::assertEquals(null, $resultFailed);
                $resultUpdate = $this->getContainer()->call([$this->userRepository, 'setUpdateStatusUser'], ['email' => $result->email, 'status' => $result->status_id]);
                self::assertNotEquals(null, $resultUpdate);
            } else {
                $this->assertTrue(false);
            }
        }
    }


    /**
     * @deprecated
     */
    public function testInvalidKey(): void
    {
        $result = $this->testStore();

        if ($result != null) {
            $resultActivate = $this->getContainer()->call([$this->activationRepository, 'setActivate'], ['key' => 'invalid key']);
            self::assertNotEquals('null', $resultActivate);
        }
    }

    /**
     * @deprecated
     */
    public function testUserCountAll(): void
    {
        $randomNumber = $this->getFaker()->numberBetween(1, 20);
        for ($i = 0; $i < $randomNumber; $i++) {
            $this->testStore();
        }

        $count = $this->getContainer()->call([$this->userRepository, 'getCountAllUser']);

        $this->assertEquals($randomNumber + 1, $count);
    }

    /**
     * @deprecated
     */
    public function testUserSearchCount(): void
    {
        $randomNumber = $this->getFaker()->numberBetween(5, 20);

        $picNumber = $this->getFaker()->numberBetween(0, $randomNumber);

        for ($i = 0; $i < $randomNumber; $i++) {
            if ($picNumber != $i) {
                $this->testStore();
            } else {
                $userData = $this->testStore();
            }
        }

        $request = new UserSearchParam();
        $request->setQ($userData->name);

        $count = $this->getContainer()->call([$this->userRepository, 'getUserSearchCount'], ['userSearchParam' => $request]);
        $this->assertEquals(1, $count);

        $request->setQ($this->getFaker()->password());
        $count = $this->getContainer()->call([$this->userRepository, 'getUserSearchCount'], ['userSearchParam' => $request]);
        $this->assertEquals(0, $count);
    }

    /**
     * @deprecated
     */
    public function testUserSearchPaging(): void
    {
        $paging = 12;

        $randomNumber = $this->getFaker()->numberBetween($paging, 20);

        $result = [];

        for ($i = 0; $i < $randomNumber; $i++) {
            $result[] = $this->testStore();
        }

        $request = new UserSearchParam();
        $request->setQ('');

        $resultSearch = $this->getContainer()->call([$this->userRepository, 'getUserSearch'], ['userSearchParam' => $request, 'paginate' => $paging]);
        $this->assertEquals($paging, count($resultSearch));

        $request->setQ($this->getFaker()->password());
        $count = $this->getContainer()->call([$this->userRepository, 'getUserSearchCount'], ['userSearchParam' => $request]);
        $this->assertEquals(0, $count);
    }

    /**
     * @deprecated
     */
    public function testUpdateUserName(): void
    {
        $result = $this->testStore();

        $newName = $this->getFaker()->name;

        $resultNew = $this->getContainer()->call([$this->userRepository, 'setUpdateName'], ['name' => $newName, 'email' => $result->email]);

        $this->assertNotEquals($result->name, $resultNew->name);
    }

    /**
     * @deprecated
     */
    public function testDeleteUserByEmail()
    {
        $randomNumber = $this->getFaker()->numberBetween(5, 20);

        $result = [];

        for ($i = 0; $i < $randomNumber; $i++) {
            $result[] = $this->testStore();
        }

        $picNumber = $this->getFaker()->numberBetween(0, $randomNumber);

        $deleteResult = $this->getContainer()->call([$this->userRepository, 'deleteUserByEmail'], ['email' => $result[$picNumber]->email]);

        self::assertEquals(true, $deleteResult);

        $resultUserData = $this->getContainer()->call([$this->userRepository, 'getUserByEmail'], ['email' => $result[$picNumber]->email]);

        self::assertEquals(null, $resultUserData);
    }
}
