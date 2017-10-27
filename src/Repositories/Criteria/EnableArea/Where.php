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

class Where extends Criteria
{
    protected $criteria;

    private $search = [];

    public function __construct(Criteria $criteria, array $search = [])
    {
        $this->criteria = $criteria;
        $this->search = $search;
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

        if (! empty($this->search['area_id'])) {
//            $model = $model->where('shop_product_area.area_id', $this->search['area_id']);
//            $model = $model->whereRaw("shop_product_area.price = (select min(price) from shop_product_area where id = shop_product_area.id and area_id = ? limit 0,1)", [$this->search['area_id']]);
        }

        return $model;
    }
}