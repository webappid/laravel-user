<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services\Responses;

use WebAppId\SmartResponse\Responses\AbstractResponse;
use WebAppId\User\Models\UserStatus;

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
