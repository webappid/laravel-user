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
     * @return int
     */
    public function getUserId(): ?int
    {
        return $this->user_id;
    }
    
    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }
    
    /**
     * @return int
     */
    public function getRoleId(): ?int
    {
        return $this->role_id;
    }
    
    /**
     * @param int $role_id
     */
    public function setRoleId(int $role_id): void
    {
        $this->role_id = $role_id;
    }
    
    
}