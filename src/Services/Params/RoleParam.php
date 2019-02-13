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
     * @return string|null
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
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
    
    
}