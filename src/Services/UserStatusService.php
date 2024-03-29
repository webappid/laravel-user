<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services;

use WebAppId\Lazy\Tools\Lazy;
use WebAppId\User\Repositories\Requests\UserStatusRepositoryRequest;
use WebAppId\User\Repositories\UserStatusRepository;
use WebAppId\User\Services\Requests\UserStatusServiceRequest;
use WebAppId\User\Services\Responses\UserStatusServiceResponse;
use WebAppId\User\Services\Responses\UserStatusServiceResponseList;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 18/04/20
 * Time: 22.17
 * Class UserStatusService
 * @package WebAppId\User\Services
 */
class UserStatusService
{
    /**
     * @param UserStatusServiceRequest $userStatusServiceRequest
     * @param UserStatusRepositoryRequest $userStatusRepositoryRequest
     * @param UserStatusRepository $userStatusRepository
     * @param UserStatusServiceResponse $userStatusServiceResponse
     * @return UserStatusServiceResponse
     */
    public function store(UserStatusServiceRequest $userStatusServiceRequest, UserStatusRepositoryRequest $userStatusRepositoryRequest, UserStatusRepository $userStatusRepository, UserStatusServiceResponse $userStatusServiceResponse): UserStatusServiceResponse
    {
        $userStatusRepositoryRequest = Lazy::copy($userStatusServiceRequest, $userStatusRepositoryRequest);

        $result = app()->call([$userStatusRepository, 'store'], ['userStatusRepositoryRequest' => $userStatusRepositoryRequest]);
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
     * @param int $id
     * @param UserStatusServiceRequest $userStatusServiceRequest
     * @param UserStatusRepositoryRequest $userStatusRepositoryRequest
     * @param UserStatusRepository $userStatusRepository
     * @param UserStatusServiceResponse $userStatusServiceResponse
     * @return UserStatusServiceResponse
     */
    public function update(int $id, UserStatusServiceRequest $userStatusServiceRequest, UserStatusRepositoryRequest $userStatusRepositoryRequest, UserStatusRepository $userStatusRepository, UserStatusServiceResponse $userStatusServiceResponse): UserStatusServiceResponse
    {
        $userStatusRepositoryRequest = Lazy::copy($userStatusServiceRequest, $userStatusRepositoryRequest);

        $result = app()->call([$userStatusRepository, 'update'], ['id' => $id, 'userStatusRepositoryRequest' => $userStatusRepositoryRequest]);
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
     * @param int $id
     * @param UserStatusRepository $userStatusRepository
     * @param UserStatusServiceResponse $userStatusServiceResponse
     * @return UserStatusServiceResponse
     */
    public function getById(int $id, UserStatusRepository $userStatusRepository, UserStatusServiceResponse $userStatusServiceResponse): UserStatusServiceResponse
    {
        $result = app()->call([$userStatusRepository, 'getById'], ['id' => $id]);
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
     * @param int $id
     * @param UserStatusRepository $userStatusRepository
     * @return bool
     */
    public function delete(int $id, UserStatusRepository $userStatusRepository): bool
    {
        return app()->call([$userStatusRepository, 'delete'], ['id' => $id]);
    }

    /**
     * @param UserStatusRepository $userStatusRepository
     * @param UserStatusServiceResponseList $userStatusServiceResponseList
     * @param int $length
     * @return UserStatusServiceResponseList
     */
    public function get(UserStatusRepository $userStatusRepository, UserStatusServiceResponseList $userStatusServiceResponseList, int $length = 12): UserStatusServiceResponseList
    {
        $result = app()->call([$userStatusRepository, 'get']);

        if (count($result) > 0) {
            $userStatusServiceResponseList->status = true;
            $userStatusServiceResponseList->message = 'Data Found';
            $userStatusServiceResponseList->userStatusList = $result;
            $userStatusServiceResponseList->count = app()->call([$userStatusRepository, 'getCount']);
        } else {
            $userStatusServiceResponseList->status = false;
            $userStatusServiceResponseList->message = 'Data Not Found';
        }

        return $userStatusServiceResponseList;
    }

    /**
     * @param UserStatusRepository $userStatusRepository
     * @return int
     */
    public function getCount(UserStatusRepository $userStatusRepository): int
    {
        return app()->call([$userStatusRepository, 'getCount']);
    }

    /**
     * @param string $q
     * @param UserStatusRepository $userStatusRepository
     * @param UserStatusServiceResponseList $userStatusServiceResponseList
     * @param int $length
     * @return UserStatusServiceResponseList
     */
    public function getWhere(string $q, UserStatusRepository $userStatusRepository, UserStatusServiceResponseList $userStatusServiceResponseList, int $length = 12): UserStatusServiceResponseList
    {
        $result = app()->call([$userStatusRepository, 'get'], ['q' => $q]);
        if (count($result) > 0) {
            $userStatusServiceResponseList->status = true;
            $userStatusServiceResponseList->message = 'Data Found';
            $userStatusServiceResponseList->userStatusList = $result;
            $userStatusServiceResponseList->count = app()->call([$userStatusRepository, 'getCount']);
            $userStatusServiceResponseList->countFiltered = app()->call([$userStatusRepository, 'getWhereCount'], ['q' => $q]);
        } else {
            $userStatusServiceResponseList->status = false;
            $userStatusServiceResponseList->message = 'Data Not Found';
        }
        return $userStatusServiceResponseList;
    }

    /**
     * @param string $q
     * @param UserStatusRepository $userStatusRepository
     * @return int
     */
    public function getWhereCount(string $q, UserStatusRepository $userStatusRepository): int
    {
        return app()->call([$userStatusRepository, 'getCount'], ['q' => $q]);
    }

}
