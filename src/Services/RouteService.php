<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Services;

use WebAppId\User\Repositories\RouteRepository;
use WebAppId\User\Repositories\Requests\RouteRepositoryRequest;
use WebAppId\User\Services\Requests\RouteServiceRequest;
use WebAppId\User\Services\Responses\RouteServiceResponse;
use WebAppId\User\Services\Responses\RouteServiceResponseList;
use WebAppId\Lazy\Tools\Lazy;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 09:09:02
 * Time: 2020/08/20
 * Class RouteService
 * @package WebAppId\User\Services
 */
class RouteService
{

    /**
     * @param RouteServiceRequest $routeServiceRequest
     * @param RouteRepositoryRequest $routeRepositoryRequest
     * @param RouteRepository $appRouteRepository
     * @param RouteServiceResponse $routeServiceResponse
     * @return RouteServiceResponse
     */
    public function store(RouteServiceRequest $routeServiceRequest, RouteRepositoryRequest $routeRepositoryRequest, RouteRepository $appRouteRepository, RouteServiceResponse $routeServiceResponse): RouteServiceResponse
    {
        $routeRepositoryRequest = Lazy::copy($routeServiceRequest, $routeRepositoryRequest, Lazy::AUTOCAST);

        $result = app()->call([$appRouteRepository, 'store'], ['routeRepositoryRequest' => $routeRepositoryRequest]);
        if ($result != null) {
            $routeServiceResponse->status = true;
            $routeServiceResponse->message = 'Store Data Success';
            $routeServiceResponse->appRoute = $result;
        } else {
            $routeServiceResponse->status = false;
            $routeServiceResponse->message = 'Store Data Failed';
        }

        return $routeServiceResponse;
    }

    /**
     * @param int $id
     * @param RouteServiceRequest $routeServiceRequest
     * @param RouteRepositoryRequest $routeRepositoryRequest
     * @param RouteRepository $appRouteRepository
     * @param RouteServiceResponse $routeServiceResponse
     * @return RouteServiceResponse
     */
    public function update(int $id, RouteServiceRequest $routeServiceRequest, RouteRepositoryRequest $routeRepositoryRequest, RouteRepository $appRouteRepository, RouteServiceResponse $routeServiceResponse): RouteServiceResponse
    {
        $routeRepositoryRequest = Lazy::copy($routeServiceRequest, $routeRepositoryRequest, Lazy::AUTOCAST);

        $result = app()->call([$appRouteRepository, 'update'], ['id' => $id, 'routeRepositoryRequest' => $routeRepositoryRequest]);
        if ($result != null) {
            $routeServiceResponse->status = true;
            $routeServiceResponse->message = 'Update Data Success';
            $routeServiceResponse->appRoute = $result;
        } else {
            $routeServiceResponse->status = false;
            $routeServiceResponse->message = 'Update Data Failed';
        }

        return $routeServiceResponse;
    }

    /**
     * @param int $id
     * @param RouteRepository $appRouteRepository
     * @param RouteServiceResponse $routeServiceResponse
     * @return RouteServiceResponse
     */
    public function getById(int $id, RouteRepository $appRouteRepository, RouteServiceResponse $routeServiceResponse): RouteServiceResponse
    {
        $result = app()->call([$appRouteRepository, 'getById'], ['id' => $id]);
        if ($result != null) {
            $routeServiceResponse->status = true;
            $routeServiceResponse->message = 'Data Found';
            $routeServiceResponse->appRoute = $result;
        } else {
            $routeServiceResponse->status = false;
            $routeServiceResponse->message = 'Data Not Found';
        }

        return $routeServiceResponse;
    }

    /**
     * @param string $name
     * @param RouteRepository $appRouteRepository
     * @param RouteServiceResponse $routeServiceResponse
     * @return RouteServiceResponse
     */
    public function getByName(string $name, RouteRepository $appRouteRepository, RouteServiceResponse $routeServiceResponse): RouteServiceResponse
    {
        $result = app()->call([$appRouteRepository, 'getByName'], ['name' => $name]);
        if ($result != null) {
            $routeServiceResponse->status = true;
            $routeServiceResponse->message = 'Data Found';
            $routeServiceResponse->appRoute = $result;
        } else {
            $routeServiceResponse->status = false;
            $routeServiceResponse->message = 'Data Not Found';
        }

        return $routeServiceResponse;
    }

    /**
     * @param int $id
     * @param RouteRepository $appRouteRepository
     * @return bool
     */
    public function delete(int $id, RouteRepository $appRouteRepository): bool
    {
        return app()->call([$appRouteRepository, 'delete'], ['id' => $id]);
    }

    /**
     * @param RouteRepository $appRouteRepository
     * @param RouteServiceResponseList $routeServiceResponseList
     * @param int $length
     * @param string|null $q
     * @return RouteServiceResponseList
     */
    public function get(RouteRepository $appRouteRepository, RouteServiceResponseList $routeServiceResponseList, int $length = 12, string $q = null): RouteServiceResponseList
    {
        $result = app()->call([$appRouteRepository, 'get'], compact('q', 'length'));
        if (count($result) > 0) {
            $routeServiceResponseList->status = true;
            $routeServiceResponseList->message = 'Data Found';
            $routeServiceResponseList->appRouteList = $result;
            $routeServiceResponseList->count = $result->total();
            $routeServiceResponseList->countFiltered = $result->count();
        } else {
            $routeServiceResponseList->status = false;
            $routeServiceResponseList->message = 'Data Not Found';
        }
        return $routeServiceResponseList;
    }

    /**
     * @param RouteRepository $appRouteRepository
     * @param string|null $q
     * @return int
     */
    public function getCount(RouteRepository $appRouteRepository, string $q = null): int
    {
        return app()->call([$appRouteRepository, 'getCount'], ['q' => $q]);
    }
}
