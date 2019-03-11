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
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    
    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    
    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
    
    /**
     * @return int
     */
    public function getStatusId(): ?int
    {
        return $this->status_id;
    }
    
    /**
     * @param mixed $status_id
     */
    public function setStatusId(int $status_id): void
    {
        $this->status_id = $status_id;
    }
    
    /**
     * @return array
     */
    public function getRoles(): ?array
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