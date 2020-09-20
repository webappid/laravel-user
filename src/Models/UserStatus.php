<?php

namespace WebAppId\User\Models;

use Illuminate\Database\Eloquent\Model;
use WebAppId\Lazy\Traits\ModelTrait;

class UserStatus extends Model
{

    use ModelTrait;

    protected $table = 'user_statuses';
    protected $fillable = ['id', 'name'];
    protected $hidden = [''];

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

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
