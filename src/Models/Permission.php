<?php

namespace WebAppId\User\Models;

use Illuminate\Database\Eloquent\Model;
use WebAppId\Lazy\Traits\ModelTrait;

class Permission extends Model
{

    use ModelTrait;

    protected $table = 'permissions';

    protected $fillable = ['id', 'name', 'description', 'created_by', 'updated_by'];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @param bool $isFresh
     * @return mixed
     */
    public function getColumns(bool $isFresh = false)
    {
        $columns = $this->getAllColumn($isFresh);

        $forbiddenField = [
            "created_at",
            "updated_at"
        ];
        foreach ($forbiddenField as $item) {
            unset($columns[$item]);
        }

        return $columns;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id');
    }
}
