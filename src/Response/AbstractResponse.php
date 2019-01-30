<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-29
 * Time: 20:34
 */

namespace WebAppId\User\Response;


abstract class AbstractResponse
{
    private $status;
    private $message;
    
    /**
     * @return mixed
     */
    public function getStatus(): ?bool
    {
        return $this->status;
    }
    
    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }
    
    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }
}