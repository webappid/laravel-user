<?php
/**
 * Created by PhpStorm.
 * UserParam: dyangalih
 * Date: 2019-01-28
 * Time: 16:39
 */

namespace WebAppId\User\Services\Params;

/**
 * Class UserParam
 * @package WebAppId\User\Services\Params
 */
class UserParam
{
    private $name;
    private $email;
    private $password;
    private $status_id;
    private $roles;
    
    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }
    
    /**
     * @return mixed
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    
    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }
    
    /**
     * @return mixed
     */
    public function getPassword(): string
    {
        return $this->password;
    }
    
    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }
    
    /**
     * @return mixed
     */
    public function getStatusId(): string
    {
        return $this->status_id;
    }
    
    /**
     * @param mixed $status_id
     */
    public function setStatusId($status_id): void
    {
        $this->status_id = $status_id;
    }
    
    /**
     * @return mixed
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
    
    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }
}