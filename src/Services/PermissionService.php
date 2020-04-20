<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services;

use WebAppId\User\Repositories\Requests\PermissionRepositoryRequest;
use WebAppId\User\Services\Requests\PermissionServiceRequest;
use WebAppId\User\Services\Responses\PermissionServiceResponse;
use WebAppId\User\Services\Responses\PermissionServiceResponseList;
use Illuminate\Container\Container;
use WebAppId\DDD\Services\BaseService;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\User\Repositories\PermissionRepository;
use WebAppId\User\Services\Contracts\PermissionServiceContract;

/**
 * Class PermissionService
 * @package WebAppId\User\Services
 */
class PermissionService extends BaseService implements PermissionServiceContract
{
    /**
     * @inheritDoc
     */
    public function store(PermissionServiceRequest $permissionServiceRequest, PermissionRepositoryRequest $permissionRepositoryRequest, PermissionRepository $permissionRepository, PermissionServiceResponse $permissionServiceResponse): PermissionServiceResponse
    {
        $permissionRepositoryRequest = Lazy::copy($permissionServiceRequest, $permissionRepositoryRequest);

        $result = $this->container->call([$permissionRepository, 'store'], ['permissionRepositoryRequest' => $permissionRepositoryRequest]);
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

        $result = $this->container->call([$permissionRepository, 'update'], ['id' => $id, 'permissionRepositoryRequest' => $permissionRepositoryRequest]);
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
        $result = $this->container->call([$permissionRepository, 'getById'], ['id' => $id]);
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
        return $this->container->call([$permissionRepository, 'delete'], ['id' => $id]);
    }

    /**
     * @inheritDoc
     */
    public function get(PermissionRepository $permissionRepository, PermissionServiceResponseList $permissionServiceResponseList, int $length = 12): PermissionServiceResponseList
    {
        $result = $this->container->call([$permissionRepository, 'get']);

        if (count($result) > 0) {
            $permissionServiceResponseList->status = true;
            $permissionServiceResponseList->message = 'Data Found';
            $permissionServiceResponseList->permission = $result;
            $permissionServiceResponseList->countAll = $this->container->call([$permissionRepository, 'getCount']);
        } else {
            $permissionServiceResponseList->status = false;
            $permissionServiceResponseList->message = 'Data Not Found';
        }

        return $permissionServiceResponseList;
    }

    /**
     * @inheritDoc
     */
    public function getCount(PermissionRepository $permissionRepository): int
    {
        return $this->container->call([$permissionRepository, 'getCount']);
    }

    /**
     * @inheritDoc
     */
    public function getWhere(string $q, PermissionRepository $permissionRepository, PermissionServiceResponseList $permissionServiceResponseList, int $length = 12): PermissionServiceResponseList
    {
        $result = $this->container->call([$permissionRepository, 'getWhere'], ['q' => $q]);
        if (count($result) > 0) {
            $permissionServiceResponseList->status = true;
            $permissionServiceResponseList->message = 'Data Found';
            $permissionServiceResponseList->permissionList = $result;
            $permissionServiceResponseList->countAll = $this->container->call([$permissionRepository, 'getCount']);
            $permissionServiceResponseList->countWhere = $this->container->call([$permissionRepository, 'getWhereCount'], ['q' => $q]);
        } else {
            $permissionServiceResponseList->status = false;
            $permissionServiceResponseList->message = 'Data Not Found';
        }
        return $permissionServiceResponseList;
    }

    /**
     * @inheritDoc
     */
    public function getWhereCount(string $q, PermissionRepository $permissionRepository): int
    {
        return $this->container->call([$permissionRepository, 'getWhereCount'], ['q' => $q]);
    }

    public function getAllPermission(PermissionRepository $permissionRepository): ?object
    {
        return $this->container->call([$permissionRepository, 'getAll']);
    }
}
