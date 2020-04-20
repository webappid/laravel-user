<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */


namespace WebAppId\User\Tests\Feature\Services;


use WebAppId\User\Services\Requests\UserServiceRequest;
use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\User\Repositories\RoleRepository;
use WebAppId\User\Services\Params\ChangePasswordParam;
use WebAppId\User\Services\Params\UserParam;
use WebAppId\User\Services\Params\UserSearchParam;
use WebAppId\User\Services\UserService;
use WebAppId\User\Tests\TestCase;
use WebAppId\User\Tests\Unit\Repositories\RoleRepositoryTest;
use WebAppId\User\Tests\Unit\Repositories\UserRepositoryTest;

class UserServiceTest extends TestCase
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var UserRepositoryTest
     */
    protected $userRepositoryTest;

    /**
     * @var RoleRepositoryTest
     */
    protected $roleRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->userService = $this->container->make(UserService::class);
            $this->userRepositoryTest = $this->container->make(UserRepositoryTest::class);
            $this->roleRepositoryTest = $this->container->make(RoleRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }

    }

    public function testGetById()
    {
        $contentServiceResponse = $this->testStore();
        $userServiceRequest = $this->getDummy();
        $result = $this->container->call([$this->userService, 'getById'], ['id' => $contentServiceResponse->user->id, 'userServiceRequest' => $userServiceRequest]);
        self::assertTrue($result->isStatus());
    }

    private function getDummy(int $number = 0): UserServiceRequest
    {
        $userRepositoryRequest = $this->container->call([$this->userRepositoryTest, 'getDummy'], ['no' => $number]);
        $userServiceRequest = null;
        try {
            $userServiceRequest = $this->container->make(UserServiceRequest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
        return Lazy::copy($userRepositoryRequest, $userServiceRequest);
    }

    public function testStore(int $number = 0)
    {
        $userServiceRequest = $this->getDummy($number);
        $userRoleList[] = $this->container->call([$this->roleRepositoryTest, 'testStore']);
        $result = $this->container->call([$this->userService, 'store'], compact('userServiceRequest', 'userRoleList'));
        self::assertTrue($result->isStatus());
        return $result;
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $result = $this->container->call([$this->userService, 'get']);
        self::assertTrue($result->isStatus());
    }

    public function testUpdate()
    {
        $contentServiceResponse = $this->testStore();
        $userServiceRequest = $this->getDummy();
        $userRoleList[] = $this->container->call([$this->roleRepositoryTest, 'testStore']);
        $result = $this->container->call([$this->userService, 'update'], ['id' => $contentServiceResponse->user->id, 'userServiceRequest' => $userServiceRequest, 'userRoleList' => $userRoleList]);
        self::assertNotEquals(null, $result);
    }

    public function testDelete()
    {
        $contentServiceResponse = $this->testStore();
        $result = $this->container->call([$this->userService, 'delete'], ['id' => $contentServiceResponse->user->id]);
        self::assertTrue($result);
    }

    public function testGetWhere()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->userService, 'getWhere'], ['q' => $q]);
        self::assertTrue($result->isStatus());
    }

    public function testGetWhereCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->userService, 'getWhereCount'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, $result);
    }

    public function roleRepository(): RoleRepository
    {
        return $this->getContainer()->make(RoleRepository::class);
    }

    public function getDummyUser(): UserParam
    {
        return $this->userRepositoryTest->getDummyUser();
    }

    public function testAddUser()
    {
        $dummy = $this->getDummyUser();

        $roles = [];
        $roles[] = $this->getFaker()->numberBetween(1, 2);

        $dummy->setRoles($roles);

        $resultUser = $this->getContainer()->call([$this->userService, 'addUser'], ['request' => $dummy]);

        if ($resultUser == null) {
            self::assertTrue(false);
        } else {
            self::assertTrue(true);

            $roleResult = $this->getContainer()->call([$this->roleRepository(), 'getRoleById'], ['id' => $resultUser->getRoles()[0]->id]);

            self::assertEquals($dummy->getName(), $resultUser->getUser()->name);
            self::assertEquals($dummy->getEmail(), $resultUser->getUser()->email);

            if ($resultUser->getRoles() != null) {
                for ($i = 0; $i < count($resultUser->getRoles()); $i++) {
                    self::assertEquals($resultUser->getRoles()[$i]->name, $roleResult->name);
                }
            } else {
                self::assertTrue(false);
            }

        }
        return $dummy;
    }


    public function testUpdatePassword()
    {
        $dummyData = $this->testAddUser();
        $changePasswordParam = new ChangePasswordParam();

        $newPassword = $this->getFaker()->password;
        $changePasswordParam->setEmail($dummyData->getEmail());
        $changePasswordParam->setPassword($newPassword);
        $resultResetPassword = $this->getContainer()->call([$this->userService, 'changePassword'], ['changePasswordParam' => $changePasswordParam, 'force' => true]);
        $this->assertEquals($resultResetPassword->getStatus(), true);
        if ($resultResetPassword->getStatus()) {
            $dummyData->setPassword($newPassword);
        }

        /**
         * check not found user
         */

        $changePasswordParam->setEmail($this->getFaker()->safeEmail);
        $resultResetPassword = $this->getContainer()->call([$this->userService, 'changePassword'], ['changePasswordParam' => $changePasswordParam]);
        $this->assertEquals($resultResetPassword->getStatus(), false);

        /**
         * test retype password not match
         */
        $changePasswordParam->setEmail($dummyData->getEmail());
        $changePasswordParam->setPassword($dummyData->getPassword());
        $changePasswordParam->setRetypePassword($this->getFaker()->password);
        $resultResetPassword = $this->getContainer()->call([$this->userService, 'changePassword'], ['changePasswordParam' => $changePasswordParam]);
        $this->assertEquals($resultResetPassword->getStatus(), false);

        /**
         * test old password not match
         */
        $changePasswordParam->setEmail($dummyData->getEmail());
        $changePasswordParam->setPassword($dummyData->getPassword());
        $changePasswordParam->setRetypePassword($dummyData->getPassword());
        $changePasswordParam->setOldPassword($this->getFaker()->password);
        $resultResetPassword = $this->getContainer()->call([$this->userService, 'changePassword'], ['changePasswordParam' => $changePasswordParam]);
        $this->assertEquals($resultResetPassword->getStatus(), false);

        /**
         * test correct value
         */

        $newPassword = $this->getFaker()->password;
        $changePasswordParam->setEmail($dummyData->getEmail());
        $changePasswordParam->setPassword($newPassword);
        $changePasswordParam->setRetypePassword($newPassword);
        $changePasswordParam->setOldPassword($dummyData->getPassword());
        $resultResetPassword = $this->getContainer()->call([$this->userService, 'changePassword'], ['changePasswordParam' => $changePasswordParam]);
        $this->assertEquals($resultResetPassword->getStatus(), true);

    }

    private function generateRandomUser(): array
    {
        $randomNumber = $this->getFaker()->numberBetween(20, 50);

        $result = [];
        for ($i = 0; $i < $randomNumber; $i++) {
            $result[] = $this->testAddUser();
        }

        return $result;
    }

    public function testSearchUser(): void
    {
        $randomUserList = $this->generateRandomUser();

        $randomNumber = count($randomUserList);

        $char = ['a', 'i', 'u', 'e', 'o'];

        $userSearchParam = new UserSearchParam();
        $userSearchParam->setQ($char[$this->getFaker()->numberBetween(0, 4)]);

        $result = $this->getContainer()->call([$this->userService, 'showUserList'], ['userSearchParam' => $userSearchParam]);
        $this->assertEquals($randomNumber + 1, $result->getRecordsTotal());
        $this->assertLessThanOrEqual($randomNumber, $result->getRecordsFiltered());
    }

    public function testSearchByEmail(): void
    {
        $randomUserList = $this->generateRandomUser();

        $randomNumber = $this->getFaker()->numberBetween(0, count($randomUserList) - 1);

        $randomUser = $randomUserList[$randomNumber];

        $userResult = $this->getContainer()->call([$this->userService, 'getUserByEmail'], ['email' => $randomUser->getEmail()]);

        $this->assertEquals($randomUser->getName(), $userResult->getData()->name);
        $this->assertEquals($randomUser->getEmail(), $userResult->getData()->email);
    }

    private function uniqueRandomNotIn($number): int
    {
        $newNumber = $this->getFaker()->numberBetween(1, 4);
        if ($newNumber == $number) {
            return $this->uniqueRandomNotIn($number);
        } else {
            return $newNumber;
        }
    }

    public function testUpdateStatusUser(): void
    {
        $resultUser = $this->testAddUser();

        $randomStatusId = $this->uniqueRandomNotIn($resultUser->getStatusId());

        $result = $this->getContainer()->call([$this->userService, 'updateUserStatus'], ['email' => $this->getFaker()->safeEmail, 'status' => $randomStatusId]);

        self::assertEquals(null, $result);

        $result = $this->getContainer()->call([$this->userService, 'updateUserStatus'], ['email' => $resultUser->getEmail(), 'status' => $randomStatusId]);

        $this->assertNotEquals(null, $result);

        $this->assertNotEquals($resultUser->getStatusId(), $result->status_id);
    }

    public function testUpdateStatusName(): void
    {
        $resultUser = $this->testAddUser();

        $name = $this->getFaker()->name;

        $result = $this->getContainer()->call([$this->userService, 'updateUserName'], ['email' => $resultUser->getEmail(), 'name' => $name]);

        self::assertNotEquals(null, $result);

        $this->assertNotEquals($resultUser->getName(), $result->name);
    }

    public function testUserDelete(): void
    {
        $randomUserList = $this->generateRandomUser();

        $randomNumber = $this->getFaker()->numberBetween(0, count($randomUserList) - 1);

        $randomUser = $randomUserList[$randomNumber];

        $resultUser = $this->getContainer()->call([$this->userService, 'deleteUserByEmail'], ['email' => $randomUser->getEmail()]);

        self::assertEquals(true, $resultUser);

        $resultSearch = $this->getContainer()->call([$this->userService, 'getUserByEmail'], ['email' => $randomUser->getEmail()]);

        self::assertEquals(null, $resultSearch->getData());
    }

    public function testUpdateUser(): void
    {
        $user = $this->testAddUser();

        $userParam = new UserParam();

        $userParam->setName($this->getFaker()->name);

        $userParam->setEmail($user->getEmail());

        $userParam->setStatusId($this->uniqueRandomNotIn($user->getStatusId()));

        if ($user->getRoles()[0] == 1) {
            $user->getRoles()[0] = 2;
        } else {
            $user->getRoles()[0] = 1;
        }

        $userParam->setRoles($user->getRoles());

        $updatedUser = $this->getContainer()->call([$this->userService, 'updateUser'], ['userParam' => $userParam]);

        self::assertNotEquals(null, $updatedUser);
    }
}
