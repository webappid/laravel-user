<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-30
 * Time: 01:00
 */

namespace WebAppId\User\Services\Responses;


use WebAppId\SmartResponse\Responses\AbstractResponse;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 04/09/19
 * Time: 18.36
 * Class ChangePasswordResponse
 * @package WebAppId\User\Response
 */
class ChangePasswordResponse extends AbstractResponse
{
    /**
     * @var string
     */
    public $email;
}