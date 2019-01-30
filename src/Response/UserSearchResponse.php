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
     * @return mixed
     */
    public function getData()
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