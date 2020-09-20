<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services;

use WebAppId\Lazy\Tools\Lazy;
use WebAppId\User\Repositories\PermissionRepository;
use WebAppId\User\Repositories\Requests\PermissionRepositoryRequest;
use WebAppId\User\Services\Contracts\PermissionServiceContract;
use WebAppId\User\Services\Requests\PermissionServiceRequest;
use WebAppId\User\Services\Responses\PermissionServiceResponse;
use WebAppId\User\Services\Responses\PermissionServiceResponseList;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 28/04/2020
 * Time: 23.39
 * Class PermissionService
 * @package WebAppId\User\Services
 */
class PermissionService implements PermissionServiceContract
{
    /**
     * @inheritDoc
     */
    public function store(PermissionServiceRequest $permissionServiceRequest, PermissionRepositoryRequest $permissionRepositoryRequest, PermissionRepository $permissionRepository, PermissionServiceResponse $permissionServiceResponse): PermissionServiceResponse
    {
        $permissionRepositoryRequest = Lazy::copy($permissionServiceRequest, $permissionRepositoryRequest);

        $result = app()->call([$permissionRepository, 'store'], ['permissionRepositoryRequest' => $permissionRepositoryRequest]);
        if ($result != null) {
            $permissionServiceResponse->status = true;
            $permissionServiceResponse->message = 'Store Data Success';
            $permissionServiceResponse->permission = $result;
        } else {
            $permissionServiceResponse->status = false;
            $permissionServiceResponse->message = 'Store Data Failed';
        }

        return $permissionServiceResponse;
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, PermissionServiceRequest $permissionServiceRequest, PermissionRepositoryRequest $permissionRepositoryRequest, PermissionRepository $permissionRepository, PermissionServiceResponse $permissionServiceResponse): PermissionServiceResponse
    {
        $permissionRepositoryRequest = Lazy::copy($permissionServiceRequest, $permissionRepositoryRequest);

        $result = app()->call([$permissionRepository, 'update'], ['id' => $id, 'permissionRepositoryRequest' => $permissionRepositoryRequest]);
        if ($result != null) {
            $permissionServiceResponse->status = true;
            $permissionServiceResponse->message = 'Update Data Success';
            $permissionServiceResponse->permission = $result;
        } else {
            $permissionServiceResponse->status = false;
            $permissionServiceResponse->message = 'Update Data Failed';
        }

        return $permissionServiceResponse;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, PermissionRepository $permissionRepository, PermissionServiceResponse $permissionServiceResponse): PermissionServiceResponse
    {
        $result = app()->call([$permissionRepository, 'getById'], ['id' => $id]);
        if ($result != null) {
            $permissionServiceResponse->status = true;
            $permissionServiceResponse->message = 'Data Found';
            $permissionServiceResponse->permission = $result;
        } else {
            $permissionServiceResponse->status = false;
            $permissionServiceResponse->message = 'Data Not Found';
        }

        return $permissionServiceResponse;
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, PermissionRepository $permissionRepository): bool
    {
        return app()->call([$permissionRepository, 'delete'], ['id' => $id]);
    }

    /**
     * @inheritDoc
     */
    public function get(PermissionRepository $permissionRepository,
                        PermissionServiceResponseList $permissionServiceResponseList,
                        int $length = 12,
                        string $q = null): PermissionServiceResponseList
    {
        $result = app()->call([$permissionRepository, 'get'], ['q' => $q]);

        if (count($result) > 0) {
            $permissionServiceResponseList->status = true;
            $permissionServiceResponseList->message = 'Data Found';
            $permissionServiceResponseList->permissionList = $result;
            $permissionServiceResponseList->count = app()->call([$permissionRepository, 'getCount']);
            $permissionServiceResponseList->countFiltered = app()->call([$permissionRepository, 'getCount'], ['q' => $q]);
        } else {
            $permissionServiceResponseList->status = false;
            $permissionServiceResponseList->message = 'Data Not Found';
        }

        return $permissionServiceResponseList;
    }

    /**
     * @inheritDoc
     */
    public function getCount(PermissionRepository $permissionRepository, string $q = null): int
    {
        return app()->call([$permissionRepository, 'getCount'], ['q' => $q]);
    }
}
