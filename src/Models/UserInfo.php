<?php
/**
 * Created by PhpStorm.
 * User: coffeekizoku
 * Date: 2017/10/23
 * Time: 11:13
 */

namespace SimpleShop\User\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInfo extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    /**
     * 数据表名
     */
    protected $table = "user_info";

    /**
     * 主键
     */
    protected $primaryKey = "id";

    /**
     * 可以被集体附值的表的字段
     */
    protected $fillable = [
        'id',
        'name',
        'real_name',
        'api_token',
        'email',
        'mobile',
        'avatar',
        'type',
        'gender',
        'address',
        'qq',
        'idcardtype',
        'remember_token',
        'idcard',
        'is_admin',
        'status'
    ];
}