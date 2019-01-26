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

class RoleRepository
{
    /**
     * @param $request
     * @param Role $role
     * @return Role|null
     */
    public function addRole($request, Role $role)
    {
        try {
            $role->name = $request->name;
            $role->description = $request->description;
            $role->save();
            return $role;
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
    public function getRoleByName($name, Role $role){
        return $role->where('name', $name)->first();
    }
    
    /**
     * @param Role $role
     * @return Role[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllRole(Role $role){
        return $role->get();
    }
    
    /**
     * @param $id
     * @param Role $role
     * @return mixed
     */
    public function getRoleById($id, Role $role){
        return $role->find($id);
    }
}