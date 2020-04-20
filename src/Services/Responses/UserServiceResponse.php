<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services\Responses;

use WebAppId\DDD\Responses\AbstractResponse;
use App\Models\User;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 07:58:10
 * Time: 2020/04/20
 * Class UserServiceResponse
 * @package App\Services\Responses
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
