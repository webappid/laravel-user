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
    
    
}