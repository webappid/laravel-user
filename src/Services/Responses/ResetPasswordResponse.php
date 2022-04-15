<?php


namespace WebAppId\User\Services\Responses;

use WebAppId\SmartResponse\Responses\AbstractResponse;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 04/09/19
 * Time: 18.36
 * Class ResetPasswordResponse
 * @package WebAppId\User\Response
 */
class ResetPasswordResponse extends AbstractResponse
{
    /**
     * @var string
     */
    public $token;
}