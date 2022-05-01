<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Repositories;

use WebAppId\User\Models\Route;
use WebAppId\User\Repositories\Requests\RouteRepositoryRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use WebAppId\Lazy\Tools\Lazy;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 09:09:02
 * Time: 2020/08/20
 * Class RouteRepository
 * @package WebAppId\User\Repositories
 */
class RouteRepository
{
    /**
     * @inheritDoc
     */
    public function store(RouteRepositoryRequest $routeRepositoryRequest, Route $route): ?Route
    {
        try {
            $route = Lazy::copy($routeRepositoryRequest, $route);
            $route->save();
            return $route;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }

    /**
     * @param Route $route
     * @param string|null $q
     * @return Builder
     */
    protected function getJoin(Route $route, string $q = null): Builder
    {
        return $route
            ->when($q != null, function ($query) use ($q) {
                return $query->where('routes.name', 'LIKE', '%' . $q . '%');
            });
    }

    /**
     * @return array
     */
    protected function getColumn(): array
    {
        return
            [
                'routes.id',
                'routes.name',
                'routes.uri',
                'routes.method',
                'routes.status',
                'routes.created_at',
                'routes.updated_at'
            ];
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, RouteRepositoryRequest $routeRepositoryRequest, Route $route): ?Route
    {
        $route = $route->find($id);
        if ($route != null) {
            try {
                $route = Lazy::copy($routeRepositoryRequest, $route);
                $route->save();
                return $route;
            } catch (QueryException $queryException) {
                report($queryException);
            }
        }
        return $route;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id, Route $route): ?Route
    {
        return $this->getJoin($route)->find($id, $this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function getByName(string $name, Route $route): ?Route
    {
        return $this->getJoin($route)->where('name', $name)->first($this->getColumn());
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, Route $route): bool
    {
        $route = $route->find($id);

        if ($route != null) {
            return $route->delete();
        } else {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(Route $route, int $length = 12, string $q = null): LengthAwarePaginator
    {
        return $this
            ->getJoin($route, $q)
            ->paginate($length, $this->getColumn())
            ->appends(request()->input());
    }

    /**
     * @inheritDoc
     */
    public function getCount(Route $route, string $q = null): int
    {
        return $this
            ->getJoin($route, $q)
            ->count();
    }

    /**
     * @inheritDoc
     */
    public function truncate(Route $route): void
    {
        DB::statement("SET foreign_key_checks=0");
        $route->truncate();
        DB::statement("SET foreign_key_checks=1");
    }

    /**
     * @inheritDoc
     */
    public function getRouteByStatus(bool $isPublic, Route $route): Collection
    {
        $status = $isPublic ? "public" : "private";
        return $route->where('status', $status)->get();
    }

    /**
     * @inheritDoc
     */
    public function getRouteByRouteNameAndRole(?string $route, array $roles, Route $routeClass): ?Route
    {
        return $routeClass
            ->leftJoin('role_routes', 'route_id', 'routes.id')
            ->where('routes.name', $route)
            ->where(function ($query) use ($roles) {
                return $query->where('routes.status', 'public')
                    ->orWhereIn('role_id', $roles);
            })->first();
    }
}
