<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-28
 * Time: 23:20
 */

namespace WebAppId\User\Services\Params;

/**
 * Class UserRoleParam
 * @package WebAppId\User\Services\Params
 */
class UserRoleParam
{
    private $user_id;
    private $role_id;
    
    /**
     * @return mixed
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }
    
    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
    }
    
    /**
     * @return mixed
     */
    public function getRoleId(): int
    {
        return $this->role_id;
    }
    
    /**
     * @param mixed $role_id
     */
    public function setRoleId($role_id): void
    {
        $this->role_id = $role_id;
    }
    
    
}