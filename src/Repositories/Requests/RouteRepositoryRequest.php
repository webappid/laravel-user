<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Repositories\Requests;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 08:21:00
 * Time: 2020/08/23
 * Class AppRouteRepositoryRequest
 * @package WebAppId\User\Repositories\Requests
 */
class RouteRepositoryRequest
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $uri;

    /**
     * @var string
     */
    public $method;

}
