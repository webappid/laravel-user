<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Repositories\Requests;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 10:46:08
 * Time: 2020/04/19
 * Class RolePermissionRepositoryRequest
 * @package WebAppId\User\Repositories\Requests
 */
class RolePermissionRepositoryRequest
{

    /**
     * @var int
     */
    public $role_id;

    /**
     * @var int
     */
    public $permission_id;

    /**
     * @var int
     */
    public $created_by;

    /**
     * @var int
     */
    public $updated_by;

}
