<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services;


use WebAppId\Lazy\Tools\Lazy;
use WebAppId\User\Repositories\Requests\RoleRepositoryRequest;
use WebAppId\User\Repositories\RoleRepository;
use WebAppId\User\Services\Requests\RoleServiceRequest;
use WebAppId\User\Services\Responses\RoleServiceResponse;
use WebAppId\User\Services\Responses\RoleServiceResponseList;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 18/04/20
 * Time: 18.51
 * Class RoleService
 * @package WebAppId\User\Services
 */
class RoleService
{
    /**
     * @param RoleServiceRequest $roleServiceRequest
     * @param RoleRepositoryRequest $roleRepositoryRequest
     * @param RoleRepository $roleRepository
     * @param RoleServiceResponse $roleServiceResponse
     * @return RoleServiceResponse
     */
    public function store(RoleServiceRequest $roleServiceRequest, RoleRepositoryRequest $roleRepositoryRequest, RoleRepository $roleRepository, RoleServiceResponse $roleServiceResponse): RoleServiceResponse
    {
        $roleRepositoryRequest = Lazy::copy($roleServiceRequest, $roleRepositoryRequest);

        $result = app()->call([$roleRepository, 'store'], ['roleRepositoryRequest' => $roleRepositoryRequest]);
        if ($result != null) {
            $roleServiceResponse->status = true;
            $roleServiceResponse->message = 'Store Data Success';
            $roleServiceResponse->role = $result;
        } else {
            $roleServiceResponse->status = false;
            $roleServiceResponse->message = 'Store Data Failed';
        }

        return $roleServiceResponse;
    }

    /**
     * @param int $id
     * @param RoleServiceRequest $roleServiceRequest
     * @param RoleRepositoryRequest $roleRepositoryRequest
     * @param RoleRepository $roleRepository
     * @param RoleServiceResponse $roleServiceResponse
     * @return RoleServiceResponse
     */
    public function update(int $id, RoleServiceRequest $roleServiceRequest, RoleRepositoryRequest $roleRepositoryRequest, RoleRepository $roleRepository, RoleServiceResponse $roleServiceResponse): RoleServiceResponse
    {
        $roleRepositoryRequest = Lazy::copy($roleServiceRequest, $roleRepositoryRequest);

        $result = app()->call([$roleRepository, 'update'], ['id' => $id, 'roleRepositoryRequest' => $roleRepositoryRequest]);
        if ($result != null) {
            $roleServiceResponse->status = true;
            $roleServiceResponse->message = 'Update Data Success';
            $roleServiceResponse->role = $result;
        } else {
            $roleServiceResponse->status = false;
            $roleServiceResponse->message = 'Update Data Failed';
        }

        return $roleServiceResponse;
    }

    /**
     * @param int $id
     * @param RoleRepository $roleRepository
     * @param RoleServiceResponse $roleServiceResponse
     * @return RoleServiceResponse
     */
    public function getById(int $id, RoleRepository $roleRepository, RoleServiceResponse $roleServiceResponse): RoleServiceResponse
    {
        $result = app()->call([$roleRepository, 'getById'], ['id' => $id]);
        if ($result != null) {
            $roleServiceResponse->status = true;
            $roleServiceResponse->message = 'Data Found';
            $roleServiceResponse->role = $result;
        } else {
            $roleServiceResponse->status = false;
            $roleServiceResponse->message = 'Data Not Found';
        }

        return $roleServiceResponse;
    }

    /**
     * @param int $id
     * @param RoleRepository $roleRepository
     * @return bool
     */
    public function delete(int $id, RoleRepository $roleRepository): bool
    {
        return app()->call([$roleRepository, 'delete'], ['id' => $id]);
    }

    /**
     * @param RoleRepository $roleRepository
     * @param RoleServiceResponseList $roleServiceResponseList
     * @param int $length
     * @param string|null $q
     * @return RoleServiceResponseList
     */
    public function get(RoleRepository $roleRepository,
                        RoleServiceResponseList $roleServiceResponseList,
                        int $length = 12,
                        string $q = null): RoleServiceResponseList
    {
        $result = app()->call([$roleRepository, 'get'], ['q' => $q]);

        if (count($result) > 0) {
            $roleServiceResponseList->status = true;
            $roleServiceResponseList->message = 'Data Found';
            $roleServiceResponseList->roleList = $result;
            $roleServiceResponseList->count = app()->call([$roleRepository, 'getCount']);
            $roleServiceResponseList->countFiltered = app()->call([$roleRepository, 'getCount'], ['q' => $q]);
        } else {
            $roleServiceResponseList->status = false;
            $roleServiceResponseList->message = 'Data Not Found';
        }

        return $roleServiceResponseList;
    }

    /**
     * @param RoleRepository $roleRepository
     * @param string|null $q
     * @return int
     */
    public function getCount(RoleRepository $roleRepository, string $q = null): int
    {
        return app()->call([$roleRepository, 'getCount'], ['q' => $q]);
    }
}
