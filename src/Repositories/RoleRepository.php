<?php
/**
 * Created by PhpStorm.
 * Users: dyangalih
 * Date: 05/11/18
 * Time: 00.38
 */

namespace WebAppId\User\Repositories;

use WebAppId\User\Models\Role;
use Illuminate\Database\QueryException;
use WebAppId\User\Services\Params\RoleParam;

class RoleRepository
{
    private $role;
    
    public function __construct(Role $role)
    {
        $this->role = $role;
    }
    
    /**
     * @param RoleParam $request
     * @return Role|null
     */
    public function addRole(RoleParam $request): ?Role
    {
        try {
            $role = $this->role->newInstance();
            $this->role->name = $request->getName();
            $this->role->description = $request->getDescription();
            $this->role->save();
            return $this->role;
        } catch (QueryException $queryException) {
            report($queryException);
            return null;
        }
    }
    
    /**
     * @param $name
     * @param Role $role
     * @return mixed
     */
    public function getRoleByName(string $name, Role $role): ?Role
    {
        return $role->where('name', $name)->first();
    }
    
    /**
     * @return Role[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllRole(): ?object
    {
        return $this->role->get();
    }
    
    /**
     * @param $id
     * @param Role $role
     * @return mixed
     */
    public function getRoleById(int $id, Role $role): ?Role
    {
        return $role->find($id);
    }
}