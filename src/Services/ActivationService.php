<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-30
 * Time: 21:03
 */

namespace WebAppId\User\Services;

use Illuminate\Container\Container;
use WebAppId\User\Repositories\ActivationRepository;
use WebAppId\User\Response\ActivateResponse;

/**
 * Class ActivationService
 * @package WebAppId\User\Services
 */
class ActivationService
{
    private $container;
    
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    /**
     * @param string $activationKey
     * @param ActivationRepository $activationRepository
     * @param ActivateResponse $activateResponse
     * @return ActivateResponse
     */
    public function activate(string $activationKey, ActivationRepository $activationRepository, ActivateResponse $activateResponse): ActivateResponse
    {
        $result = $this->container->call([$activationRepository, 'getActivationByKey'], ['key' => $activationKey]);
        if ($result == null) {
            $activateResponse->setStatus(false);
            $activateResponse->setMessage('Activation key not found');
        } elseif ($result->status != 'unused') {
            $activateResponse->setStatus(false);
            $activateResponse->setMessage('User is active');
        } elseif ($result->isValid != 'valid') {
            $activateResponse->setStatus(false);
            $activateResponse->setMessage('Key Not Valid');
        } else {
            $resultActivate = $this->container->call([$activationRepository, 'setActivate'], ['key' => $activationKey]);
            if ($resultActivate->status == 'used') {
                $activateResponse->setStatus(true);
                $activateResponse->setMessage('User Active');
            } else {
                $activateResponse->setStatus(false);
                $activateResponse->setMessage('Activation Failed, Please Contact Administrator');
            }
        }
        
        return $activateResponse;
    }
}