<?php

namespace WebAppId\User\Models;

use Illuminate\Database\Eloquent\Model;

class Activation extends Model
{
    protected $table = 'activations';
    protected $fillable = ['id','key', 'valid_until'];
    protected $hidden = ['created_at', 'updated_at'];
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}
