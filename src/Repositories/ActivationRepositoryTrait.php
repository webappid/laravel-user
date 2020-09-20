<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\User\Repositories;


use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;
use WebAppId\User\Models\Activation;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 20/09/2020
 * Time: 18.49
 * Class ActivationRepositoryTrait
 * @package WebAppId\User\Repositories
 */
trait ActivationRepositoryTrait
{
    /**
     * @inheritDoc
     */
    public function store(int $userId, Activation $activation): ?Activation
    {
        try {
            $activation->status = 'unused';
            $activation->user_id = $userId;
            $activation->key = Uuid::uuid4()->toString();
            $activation->valid_until = Carbon::now('UTC')->addHour(8)->toDateTimeString();
            $activation->save();
            return $activation;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function setActivate(string $key, Activation $activation): ?Activation
    {
        try {
            $resultActivation = $this->getByKey($key, $activation);
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

    /**
     * @inheritDoc
     */
    public function getByKey(string $key, Activation $activation): ?Activation
    {
        return $activation
            ->selectRaw(
                'status, CASE WHEN valid_until > ? THEN "valid" ELSE "invalid" END AS isValid', [Carbon::now('UTC')->toDateTimeString()]
            )
            ->where('key', $key)->first();
    }
}