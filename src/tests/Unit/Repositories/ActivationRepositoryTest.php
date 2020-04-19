<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Tests\Unit\Repositories;

use WebAppId\User\Models\Activation;
use WebAppId\User\Repositories\ActivationRepository;
use WebAppId\User\Repositories\Requests\ActivationRepositoryRequest;
use Illuminate\Contracts\Container\BindingResolutionException;
use WebAppId\User\Tests\TestCase;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 19/04/20
 * Time: 11.36
 * Class ActivationRepositoryTest
 * @package WebAppId\User\Tests\Unit\Repositories
 */
class ActivationRepositoryTest extends TestCase
{

    /**
     * @var ActivationRepository
     */
    private $activationRepository;

    private $userRepositoryTest;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        try {
            $this->activationRepository = $this->container->make(ActivationRepository::class);
            $this->userRepositoryTest = $this->container->make(UserRepositoryTest::class);
        } catch (BindingResolutionException $e) {
            report($e);
        }
    }

    public function getDummy(int $no = 0): ?ActivationRepositoryRequest
    {
        $dummy = null;
        try {
            $dummy = $this->container->make(ActivationRepositoryRequest::class);
            $user = $this->container->call([$this->userRepositoryTest, 'testStore']);
            $dummy->user_id = $user->id;
            $dummy->key = $this->getFaker()->text(255);
            $dummy->status = $this->getFaker()->text(6);
            $dummy->valid_until = $this->getFaker()->dateTime();
        } catch (BindingResolutionException $e) {
            report($e);
        }
        return $dummy;
    }

    public function testStore(int $no = 0): ?Activation
    {
        $activationRepositoryRequest = $this->getDummy($no);
        $result = $this->container->call([$this->activationRepository, 'store'], ['userId' => $activationRepositoryRequest->user_id]);
        self::assertNotEquals(null, $result);
        return $result;
    }

    public function testGetByKey()
    {
        $activation = $this->testStore();
        $result = $this->container->call([$this->activationRepository, 'getByKey'], ['key' => $activation->key]);
        self::assertNotEquals(null, $result);
    }

    public function testActivate(){
        $activation = $this->testStore();
        $result = $this->container->call([$this->activationRepository, 'setActivate'], ['key' => $activation->key]);
        self::assertNotEquals(null, $result);
    }

    public function testActivationUser(): void
    {
        $result = $this->testStore();
        if ($result != null) {
            self::assertTrue(true);
            $resultActivate = $this->getContainer()->call([$this->activationRepository, 'setActivate'], ['key' => $result->key]);
            if ($resultActivate == null) {
                self::assertTrue(false);
            } else {
                self::assertTrue(true);
                $resultActivate = $this->getContainer()->call([$this->activationRepository, 'getByKey'], ['key' => $result->key]);
                self::assertEquals($resultActivate->status, 'used');
                self::assertEquals($resultActivate->isValid, 'valid');
            }
        }
    }

    public function testActiveAlreadyUsed(): void
    {
        $result = $this->testStore();
        if ($result != null) {
            self::assertTrue(true);
            $resultActivate = $this->getContainer()->call([$this->activationRepository, 'setActivate'], ['key' => $result->key]);
            if ($resultActivate == null) {
                self::assertTrue(false);
            } else {
                self::assertTrue(true);
                $resultActivate = $this->getContainer()->call([$this->activationRepository, 'setActivate'], ['key' => $result->key]);
                self::assertEquals($resultActivate->status, 'already used');
            }
        }

    }
}
