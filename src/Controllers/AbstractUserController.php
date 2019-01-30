<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-30
 * Time: 10:24
 */

namespace WebAppId\User\Controllers;


abstract class AbstractUserController extends Controller
{
    abstract function storeResponse($response);
    abstract function showResponse($response);
    abstract function editResponse($response);
}