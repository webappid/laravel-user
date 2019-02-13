<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-29
 * Time: 20:26
 */

namespace WebAppId\User\Response;


use WebAppId\User\Models\User;

class AddUserResponse extends AbstractResponse
{
    private $user;
    private $roles;
    private $activation;
    
    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }
    
    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
    
    /**
     * @return object|null
     */
    public function getRoles(): ?object
    {
        return $this->roles;
    }
    
    /**
     * @param object $roles
     */
    public function setRoles(object $roles): void
    {
        $this->roles = $roles;
    }
    
    /**
     * @return string|null
     */
    public function getActivation(): ?string
    {
        return $this->activation;
    }
    
    /**
     * @param string $activation
     */
    public function setActivation(string $activation): void
    {
        $this->activation = $activation;
    }
    
    
}