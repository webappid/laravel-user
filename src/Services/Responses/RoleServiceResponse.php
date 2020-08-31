<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services\Responses;

use WebAppId\DDD\Responses\AbstractResponse;
use WebAppId\User\Models\Role;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 17:14:01
 * Time: 2020/04/18
 * Class RoleServiceResponse
 * @package WebAppId\User\Services\Responses
 */
class RoleServiceResponse extends AbstractResponse
{
    /**
     * @var Role
     */
    public $role;
}
