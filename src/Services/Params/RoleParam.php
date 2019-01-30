<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-28
 * Time: 16:57
 */

namespace WebAppId\User\Services\Params;

/**
 * Class RoleParam
 * @package WebAppId\User\Services\Params
 */
class RoleParam
{
    private $name;
    private $description;
    
    /**
     * @return mixed
     */
    public function getName(): ?string
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
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }
    
    
}