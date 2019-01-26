<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-26
 * Time: 13:18
 */

namespace WebAppId\User\Tests\Feature\Services;


use WebAppId\User\Repositories\RoleRepository;
use WebAppId\User\Services\UserService;
use WebAppId\User\Tests\TestCase;
use WebAppId\User\Tests\Unit\Repositories\UserRepositoryTest;

class UserServiceTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
    }
    
    public function userService()
    {
        return $this->getContainer()->make(UserService::class);
    }
    
    public function userRepositoryTest()
    {
        return $this->getContainer()->make(UserRepositoryTest::class);
    }
    
    public function roleRepository()
    {
        return $this->getContainer()->make(RoleRepository::class);
    }
    
    public function getDummyUser()
    {
        $userRepositoryTest = $this->userRepositoryTest();
        $userRepositoryTest->setUp();
        return $userRepositoryTest->getDummyUser();
    }
    
    public function testAddUser()
    {
        $dummy = $this->getDummyUser();
        $dummy->role_id = $this->getFaker()->numberBetween(1, 3);
        
        $resultUser = $this->getContainer()->call([$this->userService(), 'addUser'], ['request' => $dummy]);
        if ($resultUser == null) {
            self::assertTrue(false);
        } else {
            self::assertTrue(true);
            $roleResult = $this->getContainer()->call([$this->roleRepository(), 'getRoleById'], ['id' => $dummy->role_id]);
            
            self::assertEquals($dummy->name, $resultUser['user']->name);
            self::assertEquals($dummy->email, $resultUser['user']->email);
            
            if ($resultUser['roles'] != null) {
                for ($i = 0; $i < count($resultUser['roles']); $i++) {
                    self::assertEquals($resultUser['roles'][$i]->name, $roleResult->name);
                }
            } else {
                self::assertTrue(false);
            }
            
        }
    }
    
    
}