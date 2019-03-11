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
     * @return string|null
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
     * @return string|null
     */
    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }
    
    /**
     * @param string $oldPassword
     */
    public function setOldPassword(string $oldPassword): void
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
     * @param string $retypePassword
     */
    public function setRetypePassword(string $retypePassword): void
    {
        $this->retypePassword = $retypePassword;
    }
    
    
}