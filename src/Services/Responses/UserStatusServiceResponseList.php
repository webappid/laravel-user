<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services\Responses;

use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\DDD\Responses\AbstractResponse;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 21:27:23
 * Time: 2020/04/18
 * Class UserStatusServiceResponseList
 * @package WebAppId\User\Services\Responses
 */
class UserStatusServiceResponseList extends AbstractResponse
{
    /**
     * @var LengthAwarePaginator
     */
    public $userStatusList;

    /**
     * @var int
     */
    public $countWhere;

    /**
     * @var int
     */
    public $countAll;
}
