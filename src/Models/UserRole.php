<?php

namespace WebAppId\User\Models;

use Illuminate\Database\Eloquent\Model;
use WebAppId\Lazy\Traits\ModelTrait;

class UserRole extends Model
{

    use ModelTrait;

    protected $table = 'user_roles';
    protected $fillable = ['id', 'user_id', 'role_id'];
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
