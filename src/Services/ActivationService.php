<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-30
 * Time: 21:03
 */

namespace WebAppId\User\Services;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\DB;
use WebAppId\User\Repositories\ActivationRepository;
use WebAppId\User\Repositories\UserRepository;
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
     * @param UserRepository $userRepository
     * @param ActivateResponse $activateResponse
     * @return ActivateResponse
     */
    public function activate(string $activationKey, ActivationRepository $activationRepository, UserRepository $userRepository, ActivateResponse $activateResponse): ActivateResponse
    {
        DB::beginTransaction();
        $result = $this->container->call([$activationRepository, 'getActivationByKey'], ['key' => $activationKey]);
        if ($result == null) {
            $activateResponse->setStatus(false);
            $activateResponse->setMessage('Activation key not found');
            DB::rollBack();
        } elseif ($result->status != 'unused') {
            $activateResponse->setStatus(false);
            $activateResponse->setMessage('User is active');
            DB::rollBack();
        } elseif ($result->isValid != 'valid') {
            $activateResponse->setStatus(false);
            $activateResponse->setMessage('Key Not Valid');
            DB::rollBack();
        } else {
            $resultActivate = $this->container->call([$activationRepository, 'setActivate'], ['key' => $activationKey]);
            if ($resultActivate->status == 'used') {
                $resultStatus = $this->container->call([$userRepository, 'setUpdateStatusUser'],['email' => $resultActivate->user->email, 'status' => 2]);
                if($resultStatus != null) {
                    $activateResponse->setStatus(true);
                    $activateResponse->setMessage('User Active');
                    DB::commit();
                }else{
                    DB::rollBack();
                }
            } else {
                $activateResponse->setStatus(false);
                $activateResponse->setMessage('Activation Failed, Please Contact Administrator');
                DB::rollBack();
            }
        }
        
        return $activateResponse;
    }
}