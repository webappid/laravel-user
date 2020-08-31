<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services;

use Illuminate\Support\Facades\DB;
use WebAppId\DDD\Services\BaseService;
use WebAppId\User\Repositories\ActivationRepository;
use WebAppId\User\Repositories\UserRepository;
use WebAppId\User\Services\Contracts\ActivationServiceContract;
use WebAppId\User\Services\Responses\ActivateResponse;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 27/04/20
 * Time: 11.44
 * Class ActivationService
 * @package WebAppId\User\Services
 */
class ActivationService extends BaseService implements ActivationServiceContract
{

    /**
     * @inheritDoc
     */
    public function activate(string $activationKey,
                             ActivationRepository $activationRepository,
                             UserRepository $userRepository,
                             ActivateResponse $activateResponse): ActivateResponse
    {
        DB::beginTransaction();
        $result = $this->container->call([$activationRepository, 'getByKey'], ['key' => $activationKey]);
        if ($result == null) {
            $activateResponse->status = false;
            $activateResponse->message = 'Activation key not found';
            DB::rollBack();
        } elseif ($result->status != 'unused') {
            $activateResponse->status = false;
            $activateResponse->message = 'User is active';
            DB::rollBack();
        } elseif ($result->isValid != 'valid') {
            $activateResponse->status = false;
            $activateResponse->message = 'Key Not Valid';
            DB::rollBack();
        } else {
            $resultActivate = $this->container->call([$activationRepository, 'setActivate'], ['key' => $activationKey]);
            if ($resultActivate->status == 'used') {
                $resultStatus = $this->container->call([$userRepository, 'setUpdateStatusUser'], ['email' => $resultActivate->user->email, 'status' => 2]);
                if ($resultStatus != null) {
                    $activateResponse->status = true;
                    $activateResponse->message = 'User Active';
                    DB::commit();
                } else {
                    DB::rollBack();
                }
            } else {
                $activateResponse->status = false;
                $activateResponse->message = 'Activation Failed, Please Contact Administrator';
                DB::rollBack();
            }
        }

        return $activateResponse;
    }
}
