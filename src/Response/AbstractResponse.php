<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-29
 * Time: 20:34
 */

namespace WebAppId\User\Response;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 04/09/19
 * Time: 18.37
 * Class AbstractResponse
 * @package WebAppId\User\Response
 */
abstract class AbstractResponse
{
    public $status;
    public $message;
    
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