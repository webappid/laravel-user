<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Repositories\Contracts;

use WebAppId\User\Models\Activation;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 11:32:27
 * Time: 2020/04/19
 * Class ActivationRepositoryContract
 * @package WebAppId\User\Repositories\Contracts
 */
interface ActivationRepositoryContract
{
    /**
     * @param int $userId
     * @param Activation $activation
     * @return Activation|null
     */
    public function store(int $userId, Activation $activation): ?Activation;

    /**
     * @param string $key
     * @param Activation $activation
     * @return Activation|null
     */
    public function getByKey(string $key, Activation $activation): ?Activation;

    /**
     * @param string $key
     * @param Activation $activation
     * @return Activation|null
     */
    public function setActivate(string $key, Activation $activation): ?Activation;
}
