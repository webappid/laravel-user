<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services\Requests;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 07:58:10
 * Time: 2020/04/20
 * Class UserServiceRequest
 * @package WebAppId\User\Services\Requests
 */
class UserServiceRequest
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $email_verified_at;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $remember_token;

    /**
     * @var int
     */
    public $status_id;

}
