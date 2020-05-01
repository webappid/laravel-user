<?php


namespace WebAppId\User\Services\Contracts;

use WebAppId\User\Repositories\ActivationRepository;
use WebAppId\User\Repositories\UserRepository;
use WebAppId\User\Services\Responses\ActivateResponse;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 27/04/20
 * Time: 11.43
 * Interface ActivationServiceContract
 * @package WebAppId\User\Services\Contracts
 */
interface ActivationServiceContract
{
    /**
     * @param string $activationKey
     * @param ActivationRepository $activationRepository
     * @param UserRepository $userRepository
     * @param ActivateResponse $activateResponse
     * @return ActivateResponse
     */
    public function activate(string $activationKey,
                             ActivationRepository $activationRepository,
                             UserRepository $userRepository,
                             ActivateResponse $activateResponse): ActivateResponse;
}
