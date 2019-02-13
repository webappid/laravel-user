<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-30
 * Time: 10:33
 */

namespace WebAppId\User\Services\Params;


class UserSearchParam
{
    private $q;
    
    /**
     * @return string|null
     */
    public function getQ(): ?string
    {
        return $this->q;
    }
    
    /**
     * @param string $q
     */
    public function setQ(string $q): void
    {
        $this->q = $q;
    }
    
}