<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-29
 * Time: 07:15
 */

namespace WebAppId\User\Services\Params;

/**
 * Class UserStatusParam
 * @package WebAppId\User\Services\Params
 */
class UserStatusParam
{
    private $name;
    
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
    
    
}