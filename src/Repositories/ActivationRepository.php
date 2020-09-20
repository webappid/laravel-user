<?php
/**
 * Created by PhpStorm.
 * Users: dyangalih
 * Date: 04/11/18
 * Time: 20.23
 */

namespace WebAppId\User\Repositories;

use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;
use WebAppId\User\Models\Activation;
use WebAppId\User\Repositories\Contracts\ActivationRepositoryContract;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 28/04/2020
 * Time: 05.14
 * Class ActivationRepository
 * @package WebAppId\User\Repositories
 */
class ActivationRepository implements ActivationRepositoryContract
{
    use ActivationRepositoryTrait;
}
