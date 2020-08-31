<?php

namespace WebAppId\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    protected $table = 'user_statuses';
    protected $fillable = ['id', 'name'];
    protected $hidden = [''];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
