<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services\Requests;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22:26:58
 * Time: 2020/04/18
 * Class PermissionServiceRequest
 * @package WebAppId\User\Services\Requests
 */
class PermissionServiceRequest
{
    
    /**
     * @var string
     */
    public $name;
                
    /**
     * @var string
     */
    public $description;
                
    /**
     * @var int
     */
    public $created_by;
                
    /**
     * @var int
     */
    public $updated_by;
                
}
