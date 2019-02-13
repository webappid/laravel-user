<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-30
 * Time: 13:18
 */

namespace WebAppId\User\Response;


class UserSearchResponse extends AbstractDataTableResponse
{
    private $data;
    
    /**
     * @return object|null
     */
    public function getData():?object
    {
        return $this->data;
    }
    
    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }
}