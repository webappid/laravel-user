<?php
/**
 * Created by PhpStorm.
 */

namespace WebAppId\User\Controllers\Routes;


use WebAppId\User\Services\RouteService;
use WebAppId\User\Services\Requests\RouteServiceRequest;
use Illuminate\Support\Facades\Route;
use WebAppId\SmartResponse\Response;
use WebAppId\SmartResponse\SmartResponse;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 27/08/2020
 * Time: 07.52
 * Class RouteAutoCollectController
 * @package WebAppId\User\Controllers\AppRoutes
 */
class RouteAutoCollectController
{
    public function __invoke(RouteService $routeService, SmartResponse $smartResponse, Response $response)
    {
        $routeCollectionList = json_decode(json_encode(Route::getRoutes()->get(), true), true);
        
        if (count($routeCollectionList) == 0) {
            abort(404);
        }
        $availableRoutes = [];
        foreach ($routeCollectionList as $route) {
            if (isset($route['action']['as'])) {
                $routeName = $route['action']['as'];
                $availableRoutes[$routeName] = $routeName;

                $currentRoute = app()->call([$routeService, 'getByName'], ['name' => $routeName]);

                if (!$currentRoute->status) {
                    $routeServiceRequest = app()->make(RouteServiceRequest::class);
                    $routeServiceRequest->name = $routeName;
                    $routeServiceRequest->status = 'private';
                    $routeServiceRequest->uri = $route['uri'];
                    $routeServiceRequest->method = $route['methods'][0];
                    app()->call([$routeService, 'store'], compact('routeServiceRequest'));
                }
            }
        }

        $length = app()->call([$routeService, 'getCount']);

        $currentRouteLists = app()->call([$routeService, 'get'],compact('length'));

        foreach ($currentRouteLists->appRouteList as $currentRouteList) {
            if(!isset($availableRoutes[$currentRouteList->name])){
                try{
                    app()->call([$routeService,'delete'], ['id' => $currentRouteList->id]);
                }catch (\Exception $exception){

                }
            }
        }
        if(request()->acceptsJson()){
            return $smartResponse->saveDataSuccess($response);
        }else{
            dd('Register all new route completed');
        }
    }
}
