<?php
/**
 * Created by PhpStorm.
 * User: coffeekizoku
 * Date: 2017/10/23
 * Time: 16:33
 */

namespace SimpleShop\User\Repositories\Criteria;


use SimpleShop\Repositories\Contracts\RepositoryInterface as Repository;
use SimpleShop\Repositories\Criteria\Criteria;

class Select extends Criteria
{
    protected $select;

    public function __construct(array $select = [])
    {
        $this->select = $select;
    }

    /**
     * @param            $model
     * @param Repository $repository
     *
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        return $model->select([
            'shop_goods.name',
            'shop_goods.id',
            'shop_goods.goods_id',
            'shop_goods.cover_path',
            'shop_goods.price',
            'shop_goods.description',
            'shop_goods.status',
            'shop_goods.unit_id',
            'shop_goods.sku_id',
            'shop_user_collection.user_id',
            'shop_user_collection.type',
            'shop_user_collection.type_id',
            'shop_user_collection.id as collection_id',
        ]);
    }

}