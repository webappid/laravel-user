<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */
namespace WebAppId\User\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 07:33:07
 * Time: 2020/08/20
 * Class AppRoute
 * @package WebAppId\User\Models
 */
class Route extends Model
{
    protected $table = 'routes';
    protected $fillable = ['id', 'name', 'status', 'created_at', 'updated_at'];
    protected $hidden = [];
}
