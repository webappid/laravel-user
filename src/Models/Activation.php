<?php

namespace WebAppId\User\Models;

use Illuminate\Database\Eloquent\Model;
use WebAppId\Lazy\Traits\ModelTrait;

class Activation extends Model
{
    use ModelTrait;

    protected $table = 'activations';
    protected $fillable = ['id', 'key', 'valid_until'];
    protected $hidden = ['created_at', 'updated_at'];

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
        return $this->belongsTo(User::class);
    }
}
