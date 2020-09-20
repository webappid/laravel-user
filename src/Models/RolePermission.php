<?php

namespace WebAppId\User\Models;

use Illuminate\Database\Eloquent\Model;
use WebAppId\Lazy\Traits\ModelTrait;

class RolePermission extends Model
{
    use ModelTrait;

    protected $table = 'role_permissions';

    protected $fillable = ['role_id', 'permission_id', 'created_by', 'updated_by'];

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
}