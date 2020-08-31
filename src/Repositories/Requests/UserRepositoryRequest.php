<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Repositories\Requests;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22:56:15
 * Time: 2020/04/18
 * Class UserRepositoryRequest
 * @package WebAppId\User\Repositories\Requests
 */
class UserRepositoryRequest
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
