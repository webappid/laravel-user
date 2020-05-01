<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services;

use WebAppId\User\Repositories\Requests\UserStatusRepositoryRequest;
use WebAppId\User\Services\Requests\UserStatusServiceRequest;
use WebAppId\User\Services\Responses\UserStatusServiceResponse;
use WebAppId\User\Services\Responses\UserStatusServiceResponseList;
use Illuminate\Container\Container;
use WebAppId\DDD\Services\BaseService;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\User\Repositories\UserStatusRepository;
use WebAppId\User\Services\Contracts\UserStatusServiceContract;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 18/04/20
 * Time: 22.17
 * Class UserStatusService
 * @package WebAppId\User\Services
 */
class UserStatusService extends BaseService implements UserStatusServiceContract
{
    /**
     * @inheritDoc
     */
    public function store(UserStatusServiceRequest $userStatusServiceRequest, UserStatusRepositoryRequest $userStatusRepositoryRequest, UserStatusRepository $userStatusRepository, UserStatusServiceResponse $userStatusServiceResponse): UserStatusServiceResponse
    {
        $userStatusRepositoryRequest = Lazy::copy($userStatusServiceRequest, $userStatusRepositoryRequest);

        $result = $this->container->call([$userStatusRepository, 'store'], ['userStatusRepositoryRequest' => $userStatusRepositoryRequest]);
        if ($result != null) {
            $userStatusServiceResponse->status = true;
            $userStatusServiceResponse->message = 'Store Data Success';
            $userStatusServiceResponse->userStatus = $result;
        } else {
            $userStatusServiceResponse->status = false;
            $userStatusServiceResponse->message = 'Store Data Failed';
        }

        return $userStatusServiceResponse;
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, UserStatusServiceRequest $userStatusServiceRequest, UserStatusRepositoryRequest $userStatusRepositoryRequest, UserStatusRepository $userStatusRepository, UserStatusServiceResponse $userStatusServiceResponse): UserStatusServiceResponse
    {
        $userStatusRepositoryRequest = Lazy::copy($userStatusServiceRequest, $userStatusRepositoryRequest);

        $result = $this->container->call([$userStatusRepository, 'update'], ['id' => $id, 'userStatusRepositoryRequest' => $userStatusRepositoryRequest]);
        if ($result != null) {
            $userStatusServiceResponse->status = true;
            $userStatusServiceResponse->message = 'Update Data Success';
            $userStatusServiceResponse->userStatus = $result;
        } else {
            $userStatusServiceResponse->status = false;
            $userStatusServiceResponse->message = 'Update Data Failed';
        }

        return $userStatusServiceResponse;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, UserStatusRepository $userStatusRepository, UserStatusServiceResponse $userStatusServiceResponse): UserStatusServiceResponse
    {
        $result = $this->container->call([$userStatusRepository, 'getById'], ['id' => $id]);
        if ($result != null) {
            $userStatusServiceResponse->status = true;
            $userStatusServiceResponse->message = 'Data Found';
            $userStatusServiceResponse->userStatus = $result;
        } else {
            $userStatusServiceResponse->status = false;
            $userStatusServiceResponse->message = 'Data Not Found';
        }

        return $userStatusServiceResponse;
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, UserStatusRepository $userStatusRepository): bool
    {
        return $this->container->call([$userStatusRepository, 'delete'], ['id' => $id]);
    }

    /**
     * @inheritDoc
     */
    public function get(UserStatusRepository $userStatusRepository, UserStatusServiceResponseList $userStatusServiceResponseList, int $length = 12): UserStatusServiceResponseList
    {
        $result = $this->container->call([$userStatusRepository, 'get']);

        if (count($result) > 0) {
            $userStatusServiceResponseList->status = true;
            $userStatusServiceResponseList->message = 'Data Found';
            $userStatusServiceResponseList->userStatusList = $result;
            $userStatusServiceResponseList->count = $this->container->call([$userStatusRepository, 'getCount']);
        } else {
            $userStatusServiceResponseList->status = false;
            $userStatusServiceResponseList->message = 'Data Not Found';
        }

        return $userStatusServiceResponseList;
    }

    /**
     * @inheritDoc
     */
    public function getCount(UserStatusRepository $userStatusRepository): int
    {
        return $this->container->call([$userStatusRepository, 'getCount']);
    }

    /**
     * @inheritDoc
     */
    public function getWhere(string $q, UserStatusRepository $userStatusRepository, UserStatusServiceResponseList $userStatusServiceResponseList, int $length = 12): UserStatusServiceResponseList
    {
        $result = $this->container->call([$userStatusRepository, 'get'], ['q' => $q]);
        if (count($result) > 0) {
            $userStatusServiceResponseList->status = true;
            $userStatusServiceResponseList->message = 'Data Found';
            $userStatusServiceResponseList->userStatusList = $result;
            $userStatusServiceResponseList->count = $this->container->call([$userStatusRepository, 'getCount']);
            $userStatusServiceResponseList->countFiltered = $this->container->call([$userStatusRepository, 'getWhereCount'], ['q' => $q]);
        } else {
            $userStatusServiceResponseList->status = false;
            $userStatusServiceResponseList->message = 'Data Not Found';
        }
        return $userStatusServiceResponseList;
    }

    /**
     * @inheritDoc
     */
    public function getWhereCount(string $q, UserStatusRepository $userStatusRepository): int
    {
        return $this->container->call([$userStatusRepository, 'getCount'], ['q' => $q]);
    }

}
