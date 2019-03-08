<?php

namespace WebAppId\User\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'role_permissions';

    protected $fillable = ['role_id', 'permission_id', 'created_by', 'updated_by'];

    protected $hidden = ['created_at', 'updated_at'];
}
