<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services\Contracts;

use WebAppId\User\Repositories\Requests\UserStatusRepositoryRequest;
use WebAppId\User\Repositories\UserStatusRepository;
use WebAppId\User\Services\Requests\UserStatusServiceRequest;
use WebAppId\User\Services\Responses\UserStatusServiceResponse;
use WebAppId\User\Services\Responses\UserStatusServiceResponseList;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 18/04/20
 * Time: 22.13
 * Interface UserStatusServiceContract
 * @package WebAppId\User\Services\Contracts
 */
interface UserStatusServiceContract
{
    /**
     * @param UserStatusServiceRequest $userStatusServiceRequest
     * @param UserStatusRepositoryRequest $userStatusRepositoryRequest
     * @param UserStatusRepository $userStatusRepository
     * @param UserStatusServiceResponse $userStatusServiceResponse
     * @return UserStatusServiceResponse
     */
    public function store(UserStatusServiceRequest $userStatusServiceRequest, UserStatusRepositoryRequest $userStatusRepositoryRequest, UserStatusRepository $userStatusRepository, UserStatusServiceResponse $userStatusServiceResponse): UserStatusServiceResponse;

    /**
     * @param int $id
     * @param UserStatusServiceRequest $userStatusServiceRequest
     * @param UserStatusRepositoryRequest $userStatusRepositoryRequest
     * @param UserStatusRepository $userStatusRepository
     * @param UserStatusServiceResponse $userStatusServiceResponse
     * @return UserStatusServiceResponse
     */
    public function update(int $id, UserStatusServiceRequest $userStatusServiceRequest, UserStatusRepositoryRequest $userStatusRepositoryRequest, UserStatusRepository $userStatusRepository, UserStatusServiceResponse $userStatusServiceResponse): UserStatusServiceResponse;

    /**
     * @param int $id
     * @param UserStatusRepository $userStatusRepository
     * @param UserStatusServiceResponse $userStatusServiceResponse
     * @return UserStatusServiceResponse
     */
    public function getById(int $id, UserStatusRepository $userStatusRepository, UserStatusServiceResponse $userStatusServiceResponse): UserStatusServiceResponse;

    /**
     * @param int $id
     * @param UserStatusRepository $userStatusRepository
     * @return bool
     */
    public function delete(int $id, UserStatusRepository $userStatusRepository): bool;

    /**
     * @param UserStatusRepository $userStatusRepository
     * @param int $length
     * @param UserStatusServiceResponseList $userStatusServiceResponseList
     * @return UserStatusServiceResponseList
     */
    public function get(UserStatusRepository $userStatusRepository, UserStatusServiceResponseList $userStatusServiceResponseList, int $length = 12): UserStatusServiceResponseList;

    /**
     * @param UserStatusRepository $userStatusRepository
     * @return int
     */
    public function getCount(UserStatusRepository $userStatusRepository): int;

    /**
     * @param string $q
     * @param UserStatusRepository $userStatusRepository
     * @param UserStatusServiceResponseList $userStatusServiceResponseList
     * @param int $length
     * @return UserStatusServiceResponseList
     */
    public function getWhere(string $q, UserStatusRepository $userStatusRepository, UserStatusServiceResponseList $userStatusServiceResponseList, int $length = 12): UserStatusServiceResponseList;

    /**
     * @param string $q
     * @param UserStatusRepository $userStatusRepository
     * @return int
     */
    public function getWhereCount(string $q, UserStatusRepository $userStatusRepository): int;
}
