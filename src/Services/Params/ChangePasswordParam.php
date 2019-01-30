<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-30
 * Time: 01:07
 */

namespace WebAppId\User\Services\Params;


class ChangePasswordParam
{
    private $email;
    private $password;
    private $oldPassword;
    private $retypePassword;
    
    /**
     * @return mixed
     */
    public function getEmail()
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
    public function getPassword(): ?string
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
    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }
    
    /**
     * @param mixed $oldPassword
     */
    public function setOldPassword($oldPassword): void
    {
        $this->oldPassword = $oldPassword;
    }
    
    /**
     * @return mixed
     */
    public function getRetypePassword(): ?string
    {
        return $this->retypePassword;
    }
    
    /**
     * @param mixed $retypePassword
     */
    public function setRetypePassword($retypePassword): void
    {
        $this->retypePassword = $retypePassword;
    }
    
    
}