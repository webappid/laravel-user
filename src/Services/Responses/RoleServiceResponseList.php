<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services\Responses;

use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\DDD\Responses\AbstractResponse;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 17:14:01
 * Time: 2020/04/18
 * Class RoleServiceResponseList
 * @package WebAppId\User\Services\Responses
 */
class RoleServiceResponseList extends AbstractResponse
{
    /**
     * @var LengthAwarePaginator
     */
    public $roleList;

    /**
     * @var int
     */
    public $countWhere;

    /**
     * @var int
     */
    public $countAll;
}
