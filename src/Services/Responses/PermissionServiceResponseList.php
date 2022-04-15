<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services\Responses;

use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\SmartResponse\Responses\AbstractResponseList;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22:26:58
 * Time: 2020/04/18
 * Class PermissionServiceResponseList
 * @package WebAppId\User\Services\Responses
 */
class PermissionServiceResponseList extends AbstractResponseList
{
    /**
     * @var LengthAwarePaginator
     */
    public $permissionList;
}
