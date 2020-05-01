<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services\Contracts;

use WebAppId\User\Repositories\PermissionRepository;
use WebAppId\User\Repositories\Requests\PermissionRepositoryRequest;
use WebAppId\User\Services\Requests\PermissionServiceRequest;
use WebAppId\User\Services\Responses\PermissionServiceResponse;
use WebAppId\User\Services\Responses\PermissionServiceResponseList;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 22:26:58
 * Time: 2020/04/18
 * Class PermissionServiceContract
 * @package WebAppId\User\Services\Contracts
 */
interface PermissionServiceContract
{
    /**
     * @param PermissionServiceRequest $permissionServiceRequest
     * @param PermissionRepositoryRequest $permissionRepositoryRequest
     * @param PermissionRepository $permissionRepository
     * @param PermissionServiceResponse $permissionServiceResponse
     * @return PermissionServiceResponse
     */
    public function store(PermissionServiceRequest $permissionServiceRequest, PermissionRepositoryRequest $permissionRepositoryRequest, PermissionRepository $permissionRepository, PermissionServiceResponse $permissionServiceResponse): PermissionServiceResponse;

    /**
     * @param int $id
     * @param PermissionServiceRequest $permissionServiceRequest
     * @param PermissionRepositoryRequest $permissionRepositoryRequest
     * @param PermissionRepository $permissionRepository
     * @param PermissionServiceResponse $permissionServiceResponse
     * @return PermissionServiceResponse
     */
    public function update(int $id, PermissionServiceRequest $permissionServiceRequest, PermissionRepositoryRequest $permissionRepositoryRequest, PermissionRepository $permissionRepository, PermissionServiceResponse $permissionServiceResponse): PermissionServiceResponse;

    /**
     * @param int $id
     * @param PermissionRepository $permissionRepository
     * @param PermissionServiceResponse $permissionServiceResponse
     * @return PermissionServiceResponse
     */
    public function getById(int $id, PermissionRepository $permissionRepository, PermissionServiceResponse $permissionServiceResponse): PermissionServiceResponse;

    /**
     * @param int $id
     * @param PermissionRepository $permissionRepository
     * @return bool
     */
    public function delete(int $id, PermissionRepository $permissionRepository): bool;

    /**
     * @param PermissionRepository $permissionRepository
     * @param int $length
     * @param PermissionServiceResponseList $permissionServiceResponseList
     * @return PermissionServiceResponseList
     */
    public function get(PermissionRepository $permissionRepository, PermissionServiceResponseList $permissionServiceResponseList,int $length = 12): PermissionServiceResponseList;

    /**
     * @param PermissionRepository $permissionRepository
     * @return int
     */
    public function getCount(PermissionRepository $permissionRepository):int;
}
