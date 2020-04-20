<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services\Responses;

use Illuminate\Pagination\LengthAwarePaginator;
use WebAppId\DDD\Responses\AbstractResponse;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 07:58:10
 * Time: 2020/04/20
 * Class UserServiceResponseList
 * @package App\Services\Responses
 */
class UserServiceResponseList extends AbstractResponse
{
    /**
     * @var LengthAwarePaginator
     */
    public $userList;

    /**
     * @var int
     */
    public $countWhere;

    /**
     * @var int
     */
    public $countAll;
}
