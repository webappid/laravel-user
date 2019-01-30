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
     * @return mixed
     */
    public function getQ(): ?string
    {
        return $this->q;
    }
    
    /**
     * @param mixed $q
     */
    public function setQ($q): void
    {
        $this->q = $q;
    }
    
}