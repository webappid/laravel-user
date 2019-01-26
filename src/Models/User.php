<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-25
 * Time: 11:46
 */

namespace WebAppId\User\Models;

use App\User AS LaravelUser;

class User extends LaravelUser
{
    public function status()
    {
        return $this->hasOne(UserStatus::class, 'id', 'status_id');
    }
    
    public function activation()
    {
        return $this->hasOne(Activation::class, 'user_id', 'id');
    }
    
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }
}