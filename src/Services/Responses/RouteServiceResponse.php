<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services\Responses;

use WebAppId\User\Models\Route;
use WebAppId\SmartResponse\Responses\AbstractResponse;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 09:09:02
 * Time: 2020/08/20
 * Class AppRouteServiceResponse
 * @package WebAppId\User\Services\Responses
 */
class RouteServiceResponse extends AbstractResponse
{
    /**
     * @var AppRoute
     */
    public $appRoute;
}
