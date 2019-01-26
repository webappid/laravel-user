<?php
/**
 * Created by PhpStorm.
 * Users: dyangalih
 * Date: 03/11/18
 * Time: 16.02
 */

namespace WebAppId\User\Tests\Unit\Repositories;

use WebAppId\User\Repositories\ActivationRepository;
use WebAppId\User\Repositories\RoleRepository;
use WebAppId\User\Repositories\UserRepository;
use WebAppId\User\Repositories\UserStatusRepository;
use WebAppId\User\Repositories\UserRoleRepository;
use WebAppId\User\Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    
    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        
    }
    
    private function userRepository()
    {
        return $this->getContainer()->make(UserRepository::class);
    }
    
    private function userStatusRepository()
    {
        return $this->getContainer()->make(UserStatusRepository::class);
    }
    
    private function activationRepository()
    {
        return $this->getContainer()->make(ActivationRepository::class);
    }
    
    private function userRoleRepository(){
        return $this->getContainer()->make(UserRoleRepository::class);
    }
    
    private function roleRepository(){
        return $this->getContainer()->make(RoleRepository::class);
    }
    
    public function getDummyUser()
    {
        $objUser = new \StdClass();
        
        $objUser->name = $this->getFaker()->name;
        $objUser->email = $this->getFaker()->email;
        $objUser->status_id = '1';
        $objUser->password = $this->getFaker()->password;
        return $objUser;
    }
    
    public function createDummy($dummy)
    {
        return $this->getContainer()->call([$this->userRepository(), 'addUser'], ['request' => $dummy]);
    }
    
    public function setActivation($user_id)
    {
        $activation = new \StdClass();
        $activation->user_id = $user_id;
        
        return $this->getContainer()->call([$this->activationRepository(), 'addActivation'], ['request' => $activation]);
    }
    
    public function testAddUser()
    {
        $dummy = $this->getDummyUser();
        $result = $this->createDummy($dummy);
        $resultStatus = $this->getContainer()->call([$this->userStatusRepository(), 'getStatusById'], ['id' => $dummy->status_id]);
        
        if ($result != null) {
            
            $objUserRole = new \StdClass();
            $objUserRole->user_id = $result->id;
            $objUserRole->role_id = $this->getFaker()->numberBetween(1,3);
            
            $resultUserRole = $this->getContainer()->call([$this->userRoleRepository(),'addUserRole'],['request' => $objUserRole]);
            
            if($resultUserRole==null){
                self::assertTrue(false);
            }else{
                self::assertTrue(true);
                self::assertEquals($objUserRole->user_id, $resultUserRole->user_id);
                self::assertEquals($objUserRole->role_id, $resultUserRole->role_id);
                
                $roleResult = $this->getContainer()->call([$this->roleRepository(),'getRoleById'],['id' => $objUserRole->role_id]);
                if($roleResult==null){
                    self::assertTrue(false);
                }else{
                    self::assertTrue(true);
                    self::assertEquals($result->roles[0]->name, $roleResult->name);
                }
            }
            
            $activationResult = $this->setActivation($result->id);
            if ($activationResult != null) {
                $this->assertTrue(true);
            } else {
                $this->assertTrue(false);
            }
            
            $this->assertTrue(true);
            $this->assertEquals($dummy->status_id, $result->status_id);
            $this->assertEquals($resultStatus->name, $result->status->name);
            
            return $result;
        } else {
            $this->assertTrue(false);
            return null;
        }
    }
    
    public function testGetUserByEmail()
    {
        $result = $this->createDummy($this->getDummyUser());
        
        if ($result != null) {
            $result = $this->getContainer()->call([$this->userRepository(), 'getUserByEmail'], ['email' => $result->email]);
            if ($result != null) {
                $this->assertTrue(true);
            } else {
                $this->assertTrue(false);
            }
        }
    }
    
    public function testUpdateUserPassword()
    {
        $result = $this->testAddUser();
        if ($result != null) {
            $result = $this->getContainer()->call([$this->userRepository(), 'getUserByEmail'], ['email' => $result->email]);
            if ($result != null) {
                $result->password = $this->getFaker()->password;
                $resultUpdate = $this->getContainer()->call([$this->userRepository(), 'setUpdatePassword'], ['email' => $result->email, 'password' => $result->password]);
                if ($resultUpdate == null) {
                    $this->assertTrue(false);
                } else {
                    $this->assertTrue(true);
                }
            } else {
                $this->assertTrue(false);
            }
        }
    }
    
    public function testUpdateUserStatus()
    {
        $result = $this->testAddUser();
        if ($result != null) {
            $result = $this->getContainer()->call([$this->userRepository(), 'getUserByEmail'], ['email' => $result->email]);
            if ($result != null) {
                $result->status_id = $this->getFaker()->numberBetween(1, 4);
                $resultUpdate = $this->getContainer()->call([$this->userRepository(), 'setUpdateStatusUser'], ['email' => $result->email, 'status' => $result->status_id]);
                if ($resultUpdate == null) {
                    $this->assertTrue(false);
                } else {
                    $this->assertTrue(true);
                }
            } else {
                $this->assertTrue(false);
            }
        }
    }
    
    public function testActivationUser()
    {
        $result = $this->testAddUser();
        if ($result != null) {
            self::assertTrue(true);
            $resultActivate = $this->getContainer()->call([$this->activationRepository(), 'setActivate'], ['key' => $result->activation->key]);
            if ($resultActivate == null) {
                self::assertTrue(false);
            } else {
                self::assertTrue(true);
                $resultActivate = $this->getContainer()->call([$this->activationRepository(), 'getActivationByKey'], ['key' => $result->activation->key]);
                self::assertEquals($resultActivate->status, 'used');
                self::assertEquals($resultActivate->isValid, 'valid');
            }
        }
        
    }
    
    public function testInvalidKey()
    {
        $result = $this->testAddUser();
        
        if ($result != null) {
            self::assertTrue(true);
            $resultActivate = $this->getContainer()->call([$this->activationRepository(), 'setActivate'], ['key' => 'invalid key']);
            if ($resultActivate == null) {
                self::assertFalse(false);
            } else {
                self::assertFalse(true);
            }
        }
    }
    
    public function testActiveAlreadyUsed()
    {
        $result = $this->testAddUser();
        if ($result != null) {
            self::assertTrue(true);
            $resultActivate = $this->getContainer()->call([$this->activationRepository(), 'setActivate'], ['key' => $result->activation->key]);
            if ($resultActivate == null) {
                self::assertTrue(false);
            } else {
                self::assertTrue(true);
                $resultActivate = $this->getContainer()->call([$this->activationRepository(), 'setActivate'], ['key' => $result->activation->key]);
                self::assertEquals($resultActivate->status, 'already used');
            }
        }
        
    }
    
}