<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */


namespace WebAppId\User\Tests\Feature\Services;


use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\User\Services\Requests\ChangePasswordRequest;
use WebAppId\User\Services\Requests\UserServiceRequest;
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
        self::assertTrue($result->status);
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
        self::assertTrue($result->status);
        return $result;
    }

    public function testGet()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $result = $this->container->call([$this->userService, 'get']);
        self::assertTrue($result->status);
    }

    public function testUpdate()
    {
        $contentServiceResponse = $this->testStore();
        $userServiceRequest = $this->getDummy();
        $userServiceRequest->password = null;
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
        $result = $this->container->call([$this->userService, 'get'], ['q' => $q]);
        self::assertTrue($result->status);
    }

    public function testGetWhereCount()
    {
        for ($i = 0; $i < $this->getFaker()->numberBetween(50, $this->getFaker()->numberBetween(50, 100)); $i++) {
            $this->testStore($i);
        }
        $string = 'aiueo';
        $q = $string[$this->getFaker()->numberBetween(0, strlen($string) - 1)];
        $result = $this->container->call([$this->userService, 'getCount'], ['q' => $q]);
        self::assertGreaterThanOrEqual(1, $result);
    }

    public function testUpdatePassword()
    {
        $dummyData = $this->testStore();
        try {
            $changePasswordRequest = $this->container->make(ChangePasswordRequest::class);

            $newPassword = $this->getFaker()->password;
            $changePasswordRequest->email = $dummyData->user->email;
            $changePasswordRequest->password = $newPassword;
            $resultResetPassword = $this->container->call([$this->userService, 'changePassword'], ['changePasswordRequest' => $changePasswordRequest, 'force' => true]);
            $this->assertEquals($resultResetPassword->getStatus(), true);
            if ($resultResetPassword->getStatus()) {
                $dummyData->user->password = $newPassword;
            }

            /**
             * check not found user
             */

            $changePasswordRequest->email = $this->getFaker()->safeEmail;
            $resultResetPassword = $this->container->call([$this->userService, 'changePassword'], ['changePasswordRequest' => $changePasswordRequest]);
            $this->assertEquals($resultResetPassword->getStatus(), false);

            /**
             * test retype password not match
             */
            $changePasswordRequest->email = $dummyData->user->email;
            $changePasswordRequest->password = $dummyData->user->password;
            $changePasswordRequest->retypePassword = $this->getFaker()->password;
            $resultResetPassword = $this->container->call([$this->userService, 'changePassword'], ['changePasswordRequest' => $changePasswordRequest]);
            $this->assertEquals($resultResetPassword->getStatus(), false);

            /**
             * test old password not match
             */
            $changePasswordRequest->email = $dummyData->user->email;
            $changePasswordRequest->password = $dummyData->user->password;
            $changePasswordRequest->retypePassword = $dummyData->user->password;
            $changePasswordRequest->oldPassword = $this->getFaker()->password;
            $resultResetPassword = $this->container->call([$this->userService, 'changePassword'], ['changePasswordRequest' => $changePasswordRequest]);
            $this->assertEquals($resultResetPassword->getStatus(), false);

            /**
             * test correct value
             */

            $newPassword = $this->getFaker()->password;
            $changePasswordRequest->email = $dummyData->user->email;
            $changePasswordRequest->password = $newPassword;
            $changePasswordRequest->retypePassword = $newPassword;
            $changePasswordRequest->oldPassword = $dummyData->user->password;
            $resultResetPassword = $this->container->call([$this->userService, 'changePassword'], ['changePasswordRequest' => $changePasswordRequest]);
            $this->assertEquals($resultResetPassword->getStatus(), true);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    private function generateRandomUser(): array
    {
        $randomNumber = $this->getFaker()->numberBetween(20, 50);

        $result = [];
        for ($i = 0; $i < $randomNumber; $i++) {
            $result[] = $this->testStore();
        }

        return $result;
    }

    public function testSearchUser(): void
    {
        $randomUserList = $this->generateRandomUser();

        $randomNumber = count($randomUserList);

        $char = ['a', 'i', 'u', 'e', 'o'];

        $result = $this->container->call([$this->userService, 'get'], ['q' => $char[$this->getFaker()->numberBetween(0, 4)]]);

        $this->greaterThanOrEqual(1, $result->count);
        $this->greaterThanOrEqual($randomNumber, $result->countFiltered);
    }

    public function testSearchByEmail(): void
    {
        $randomUserList = $this->generateRandomUser();

        $randomNumber = $this->getFaker()->numberBetween(0, count($randomUserList) - 1);

        $randomUser = $randomUserList[$randomNumber];

        $userResult = $this->container->call([$this->userService, 'getByEmail'], ['email' => $randomUser->user->email]);

        $this->assertEquals($randomUser->user->name, $userResult->user->name);
        $this->assertEquals($randomUser->user->email, $userResult->user->email);
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
        $resultUser = $this->testStore();

        $randomStatusId = $this->uniqueRandomNotIn($resultUser->user->status_id);

        $result = $this->container->call([$this->userService, 'updateUserStatus'], ['email' => $this->getFaker()->safeEmail, 'status' => $randomStatusId]);

        self::assertEquals(null, $result);

        $result = $this->container->call([$this->userService, 'updateUserStatus'], ['email' => $resultUser->user->email, 'status' => $randomStatusId]);

        $this->assertNotEquals(null, $result);

        $this->assertNotEquals($resultUser->user->user_id, $result->status_id);
    }

    public function testUpdateStatusName(): void
    {
        $resultUser = $this->testStore();

        $name = $this->getFaker()->name;

        $result = $this->container->call([$this->userService, 'updateUserName'], ['email' => $resultUser->user->email, 'name' => $name]);

        self::assertNotEquals(null, $result);

        $this->assertNotEquals($resultUser->user->name, $result->name);
    }

    public function testUserDelete(): void
    {
        $randomUserList = $this->generateRandomUser();

        $randomNumber = $this->getFaker()->numberBetween(0, count($randomUserList) - 1);

        $randomUser = $randomUserList[$randomNumber];

        $resultUser = $this->container->call([$this->userService, 'deleteByEmail'], ['email' => $randomUser->user->email]);

        self::assertEquals(true, $resultUser);

        $resultSearch = $this->container->call([$this->userService, 'getByEmail'], ['email' => $randomUser->user->email]);

        self::assertEquals(null, $resultSearch->user);
    }
}
