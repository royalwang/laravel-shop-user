<?php
/**
 * Created by PhpStorm.
 * User: coffeekizoku
 * Date: 2017/10/18
 * Time: 11:13
 */

namespace SimpleShop\User\Models;


use Illuminate\Database\Eloquent\Model;

class ShopUserCollection extends Model
{
    /**
     * 主键
     */
    protected $primaryKey = "id";

    /**
     * 表名
     *
     * @var string
     */
    protected $table = 'shop_user_collection';

    /**
     * 白名单列表
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
        'type_id'
    ];

    /**
     * 商品的点赞类型
     */
    const TYPE_GOODS = 1;
}