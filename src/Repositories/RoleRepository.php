<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Repositories;

use WebAppId\User\Repositories\Contracts\RoleRepositoryContract;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 17:14:01
 * Time: 2020/04/18
 * Class RoleRepository
 * @package WebAppId\User\Repositories
 */
class RoleRepository implements RoleRepositoryContract
{
    use RoleRepositoryTrait;

    public function __construct()
    {

    }
}
