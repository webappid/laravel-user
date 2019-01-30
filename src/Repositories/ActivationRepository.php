<?php
/**
 * Created by PhpStorm.
 * Users: dyangalih
 * Date: 04/11/18
 * Time: 20.23
 */

namespace WebAppId\User\Repositories;

use WebAppId\User\Models\Activation;
use Faker\Factory as Faker;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Webpatser\Uuid\Uuid;

/**
 * Class ActivationRepository
 * @package WebAppId\UserParam\Repositories
 */
class ActivationRepository
{
    /**
     * @param int $userId
     * @param Activation $activation
     * @return Activation|null
     * @throws \Exception
     */
    public function addActivation(int $userId, Activation $activation): ?Activation
    {
        try {
            $activation->status = 'unused';
            $activation->user_id = $userId;
            $activation->key = Uuid::generate(4)->string;
            $activation->valid_until = Carbon::now('UTC')->addHour(8)->toDateTimeString();
            $activation->save();
            return $activation;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }
    
    /**
     * @param $key
     * @param Activation $activation
     * @return mixed
     */
    public function getActivationByKey($key, Activation $activation): ?Activation
    {
        return $activation
            ->selectRaw(
                'status, CASE WHEN valid_until > ? THEN "valid" ELSE "invalid" END AS isValid', [Carbon::now('UTC')->toDateTimeString()]
            )
            ->where('key', $key)->first();
    }
    
    
    /**
     * @param $key
     * @param Activation $activation
     * @return Activation|null
     */
    public function setActivate($key, Activation $activation): ?Activation
    {
        try {
            $resultActivation = $this->getActivationByKey($key, $activation);
            if ($resultActivation == null) {
                return null;
            } else {
                if ($resultActivation->isValid == 'valid' && $resultActivation->status != 'used') {
                    $resultActivation = $activation->where('key', $key)->first();
                    $resultActivation->status = 'used';
                    $resultActivation->save();
                } else {
                    $resultActivation->status = 'already used';
                }
                
                return $resultActivation;
            }
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }
}