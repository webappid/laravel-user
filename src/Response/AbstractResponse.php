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
     * @return bool|null
     */
    public function getStatus(): ?bool
    {
        return $this->status;
    }
    
    /**
     * @param bool $status
     */
    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }
    
    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }
    
    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}