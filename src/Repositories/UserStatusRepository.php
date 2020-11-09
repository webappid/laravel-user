<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */


namespace WebAppId\User\Repositories;

use WebAppId\User\Repositories\Contracts\UserStatusRepositoryContract;


/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 18/04/20
 * Time: 22.07
 * Class UserStatusRepository
 * @package WebAppId\User\Repositories
 */
class UserStatusRepository implements UserStatusRepositoryContract
{
    use UserStatusRepositoryTrait;
}
