<?php
/**
 * Created by PhpStorm.
 * User: coffeekizoku
 * Date: 2017/10/23
 * Time: 16:10
 */

namespace SimpleShop\User\Repositories\Criteria\EnableArea;


use SimpleShop\Repositories\Contracts\RepositoryInterface as Repository;
use SimpleShop\Repositories\Criteria\Criteria;

class Join extends Criteria
{
    protected $criteria;

    protected $join;

    public function __construct(Criteria $criteria, $join)
    {
        $this->criteria = $criteria;
        $this->join = $join;
    }

    /**
     * @param            $model
     * @param Repository $repository
     *
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        $model = $this->criteria->apply($model, $repository);

        $model = $model->join('shop_goods_product', 'shop_goods_product.goods_id', '=', 'shop_goods.id')
            ->leftJoin('shop_product_area', function ($query) {
                $query->on('shop_product_area.product_id', '=', 'shop_goods_product.id')
                    ->where('shop_product_area.area_id', $this->join['area_id']);
            });

        return $model;
    }
}