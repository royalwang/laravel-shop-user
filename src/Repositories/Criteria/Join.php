<?php
/**
 * Created by PhpStorm.
 * User: coffeekizoku
 * Date: 2017/10/18
 * Time: 16:04
 */

namespace SimpleShop\User\Repositories\Criteria;


use SimpleShop\Repositories\Contracts\RepositoryInterface as Repository;
use SimpleShop\Repositories\Criteria\Criteria;

class Join extends Criteria
{
    /**
     * @param            $model
     * @param Repository $repository
     *
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        $model = $model->join('shop_goods', function ($join) {
            $join->on('shop_goods.id', '=', 'shop_user_collection.type_id');
        });

        return $model;
    }
}