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
    
    /**
     * @return mixed
     */
    public function getUser(): ?User
    {
        return $this->user;
    }
    
    /**
     * @param mixed $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
    
    /**
     * @return mixed
     */
    public function getRoles(): ?object
    {
        return $this->roles;
    }
    
    /**
     * @param mixed $roles
     */
    public function setRoles(object $roles): void
    {
        $this->roles = $roles;
    }
}