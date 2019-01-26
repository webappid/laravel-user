<?php

namespace WebAppId\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'user_roles';
    protected $fillable = ['id', 'user_id', 'role_id'];
    protected $hidden = ['created_at', 'updated_at'];
}
