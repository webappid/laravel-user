<?php

namespace WebAppId\User\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['id', 'name', 'description'];
    protected $hidden = ['created_at', 'updated_at'];
    
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }
}
