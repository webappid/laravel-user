<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-30
 * Time: 01:07
 */

namespace WebAppId\User\Services\Requests;


class ChangePasswordRequest
{
    public $email;
    public $password;
    public $oldPassword;
    public $retypePassword;

}