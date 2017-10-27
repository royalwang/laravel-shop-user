<?php
/**
 *------------------------------------------------------
 * UserAddressModel.php
 *------------------------------------------------------
 *
 * @author    qqiu@qq.com
 * @date      2017/10/10 10:22
 * @version   V1.0
 *
 */

namespace SimpleShop\User\Models;

use Illuminate\Database\Eloquent\Model;

class ShopUserAddressModel extends Model
{
    /**
     * 数据表名
     */
    protected $table = "shop_user_address";

    /**
     * 主键
     */
    protected $primaryKey = "id";

    /**
     * 可以被集体附值的表的字段
     */
    protected $fillable = [
        'user_id',
        'byname',
        'province',
        'city',
        'county',
        'region_desc',
        'address',
        'consignee',
        'telephone',
        'default'
    ];

}