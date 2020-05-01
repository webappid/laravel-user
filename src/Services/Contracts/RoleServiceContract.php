<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services\Contracts;

use WebAppId\User\Repositories\Requests\RoleRepositoryRequest;
use WebAppId\User\Repositories\RoleRepository;
use WebAppId\User\Services\Requests\RoleServiceRequest;
use WebAppId\User\Services\Responses\RoleServiceResponse;
use WebAppId\User\Services\Responses\RoleServiceResponseList;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 17:14:01
 * Time: 2020/04/18
 * Class RoleServiceContract
 * @package WebAppId\User\Services\Contracts
 */
interface RoleServiceContract
{
    /**
     * @param RoleServiceRequest $roleServiceRequest
     * @param RoleRepositoryRequest $roleRepositoryRequest
     * @param RoleRepository $roleRepository
     * @param RoleServiceResponse $roleServiceResponse
     * @return RoleServiceResponse
     */
    public function store(RoleServiceRequest $roleServiceRequest,
                          RoleRepositoryRequest $roleRepositoryRequest,
                          RoleRepository $roleRepository,
                          RoleServiceResponse $roleServiceResponse): RoleServiceResponse;

    /**
     * @param int $id
     * @param RoleServiceRequest $roleServiceRequest
     * @param RoleRepositoryRequest $roleRepositoryRequest
     * @param RoleRepository $roleRepository
     * @param RoleServiceResponse $roleServiceResponse
     * @return RoleServiceResponse
     */
    public function update(int $id, RoleServiceRequest $roleServiceRequest,
                           RoleRepositoryRequest $roleRepositoryRequest,
                           RoleRepository $roleRepository,
                           RoleServiceResponse $roleServiceResponse): RoleServiceResponse;

    /**
     * @param int $id
     * @param RoleRepository $roleRepository
     * @param RoleServiceResponse $roleServiceResponse
     * @return RoleServiceResponse
     */
    public function getById(int $id,
                            RoleRepository $roleRepository,
                            RoleServiceResponse $roleServiceResponse): RoleServiceResponse;

    /**
     * @param int $id
     * @param RoleRepository $roleRepository
     * @return bool
     */
    public function delete(int $id, RoleRepository $roleRepository): bool;

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
                        string $q = null): RoleServiceResponseList;

    /**
     * @param RoleRepository $roleRepository
     * @param string|null $q
     * @return int
     */
    public function getCount(RoleRepository $roleRepository,
                             string $q = null): int;
}
