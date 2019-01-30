<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-30
 * Time: 10:26
 */

namespace WebAppId\User\Controllers;


use Illuminate\Container\Container;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller AS BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    private $container;
    
    protected function getContainer()
    {
        if ($this->container == null) {
            $this->container = new Container();
        }
        
        return $this->container;
    }
}