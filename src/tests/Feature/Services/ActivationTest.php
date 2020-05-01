<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Tests\Feature\Services;

use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\User\Services\ActivationService;
use WebAppId\User\Services\UserService;
use WebAppId\User\Tests\TestCase;


class ActivationTest extends TestCase
{
    /**
     * @var UserServiceTest
     */
    private $userServiceTest;
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var UserService
     */
    private $activationService;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->userService = $this->container->make(UserService::class);
            $this->activationService = $this->container->make(ActivationService::class);
            $this->userServiceTest = $this->container->make(UserServiceTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function testActivation(): void
    {
        $dummyUser = $this->container->call([$this->userServiceTest, 'testStore']);
        $resultUser = $this->container->call([$this->userService, 'getByEmail'], ['email' => $dummyUser->user->email]);

        $activationKey = $resultUser->user->activation->key;

        /**
         * test expired key
         */
        $resultUser->user->activation->valid_until = Carbon::now('UTC')->addHour(-8)->toDateTimeString();
        $resultUser->user->activation->save();
        $resultExpiredKey = $this->container->call([$this->activationService, 'activate'], ['activationKey' => $activationKey]);
        self::assertEquals(false, $resultExpiredKey->status);

        $resultUser->user->activation->valid_until = Carbon::now('UTC')->addHour(8)->toDateTimeString();
        $resultUser->user->activation->save();

        /**
         * test not valid uuid
         */
        $resultNotValid = $this->container->call([$this->activationService, 'activate'], ['activationKey' => $this->getFaker()->uuid]);
        self::assertEquals(false, $resultNotValid->status);

        /**
         *  test success activate user
         */
        $resultActivation = $this->container->call([$this->activationService, 'activate'], ['activationKey' => $activationKey]);

        self::assertEquals(true, $resultActivation->status);

        if ($resultActivation->status) {
            $resultUser = $this->container->call([$this->userService, 'getByEmail'], ['email' => $dummyUser->user->email]);
            self::assertEquals(2, $resultUser->user['status_id']);
        }

        /**
         *  test double activation
         */
        $resultUsedKey = $this->container->call([$this->activationService, 'activate'], ['activationKey' => $activationKey]);
        self::assertEquals(false, $resultUsedKey->status);
    }
}
