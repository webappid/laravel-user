<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services\Responses;

use WebAppId\DDD\Responses\AbstractResponse;
use WebAppId\User\Models\Permission;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22:26:58
 * Time: 2020/04/18
 * Class PermissionServiceResponse
 * @package WebAppId\User\Services\Responses
 */
class PermissionServiceResponse extends AbstractResponse
{
    /**
     * @var Permission
     */
    public $permission;
}
