<?php

namespace WebAppId\User\Models;

class UserLogin extends User
{
    protected $table = 'users';
    
    /**
     * @param bool $isFresh
     * @return mixed
     */
    public function getColumns(bool $isFresh = false)
    {
        $columns = $this->getAllColumn($isFresh);

        return $columns;
    }
}
