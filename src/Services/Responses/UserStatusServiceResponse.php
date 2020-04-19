<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services\Responses;

use WebAppId\User\Models\UserStatus;
use WebAppId\DDD\Responses\AbstractResponse;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 21:27:23
 * Time: 2020/04/18
 * Class UserStatusServiceResponse
 * @package WebAppId\User\Services\Responses
 */
class UserStatusServiceResponse extends AbstractResponse
{
    /**
     * @var UserStatus
     */
    public $userStatus;
}
