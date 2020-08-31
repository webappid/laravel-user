<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services;


use WebAppId\DDD\Services\BaseService;
use WebAppId\DDD\Tools\Lazy;
use WebAppId\User\Repositories\Requests\RoleRepositoryRequest;
use WebAppId\User\Repositories\RoleRepository;
use WebAppId\User\Services\Contracts\RoleServiceContract;
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
class RoleService extends BaseService implements RoleServiceContract
{
    /**
     * @inheritDoc
     */
    public function store(RoleServiceRequest $roleServiceRequest, RoleRepositoryRequest $roleRepositoryRequest, RoleRepository $roleRepository, RoleServiceResponse $roleServiceResponse): RoleServiceResponse
    {
        $roleRepositoryRequest = Lazy::copy($roleServiceRequest, $roleRepositoryRequest);

        $result = $this->container->call([$roleRepository, 'store'], ['roleRepositoryRequest' => $roleRepositoryRequest]);
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
     * @inheritDoc
     */
    public function update(int $id, RoleServiceRequest $roleServiceRequest, RoleRepositoryRequest $roleRepositoryRequest, RoleRepository $roleRepository, RoleServiceResponse $roleServiceResponse): RoleServiceResponse
    {
        $roleRepositoryRequest = Lazy::copy($roleServiceRequest, $roleRepositoryRequest);

        $result = $this->container->call([$roleRepository, 'update'], ['id' => $id, 'roleRepositoryRequest' => $roleRepositoryRequest]);
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
     * @inheritDoc
     */
    public function getById(int $id, RoleRepository $roleRepository, RoleServiceResponse $roleServiceResponse): RoleServiceResponse
    {
        $result = $this->container->call([$roleRepository, 'getById'], ['id' => $id]);
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
     * @inheritDoc
     */
    public function delete(int $id, RoleRepository $roleRepository): bool
    {
        return $this->container->call([$roleRepository, 'delete'], ['id' => $id]);
    }

    /**
     * @inheritDoc
     */
    public function get(RoleRepository $roleRepository,
                        RoleServiceResponseList $roleServiceResponseList,
                        int $length = 12,
                        string $q = null): RoleServiceResponseList
    {
        $result = $this->container->call([$roleRepository, 'get'], ['q' => $q]);

        if (count($result) > 0) {
            $roleServiceResponseList->status = true;
            $roleServiceResponseList->message = 'Data Found';
            $roleServiceResponseList->roleList = $result;
            $roleServiceResponseList->count = $this->container->call([$roleRepository, 'getCount']);
            $roleServiceResponseList->countFiltered = $this->container->call([$roleRepository, 'getCount'], ['q' => $q]);
        } else {
            $roleServiceResponseList->status = false;
            $roleServiceResponseList->message = 'Data Not Found';
        }

        return $roleServiceResponseList;
    }

    /**
     * @inheritDoc
     */
    public function getCount(RoleRepository $roleRepository, string $q = null): int
    {
        return $this->container->call([$roleRepository, 'getCount'], ['q' => $q]);
    }
}
