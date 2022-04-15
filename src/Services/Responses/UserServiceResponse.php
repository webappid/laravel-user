<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services\Responses;

use WebAppId\SmartResponse\Responses\AbstractResponse;
use WebAppId\User\Models\User;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 07:58:10
 * Time: 2020/04/20
 * Class UserServiceResponse
 * @package WebAppId\User\Services\Responses
 */
class UserServiceResponse extends AbstractResponse
{
    /**
     * @var User
     */
    public $user;

    /**
     * @var string
     */
    public $activationKey;

    /**
     * @var array
     */
    public $roleList;
}
