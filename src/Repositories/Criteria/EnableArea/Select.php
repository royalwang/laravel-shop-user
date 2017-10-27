<?php
/**
 * Created by PhpStorm.
 * User: coffeekizoku
 * Date: 2017/10/23
 * Time: 16:36
 */

namespace SimpleShop\User\Repositories\Criteria\EnableArea;


use SimpleShop\Repositories\Contracts\RepositoryInterface as Repository;
use SimpleShop\Repositories\Criteria\Criteria;

class Select extends Criteria
{
    protected $criteria;

    protected $select;

    public function __construct(Criteria $criteria, array $select = [])
    {
        $this->criteria = $criteria;
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
        $model = $this->criteria->apply($model, $repository);

        $model = $model->select(\DB::raw(
            "`shop_goods`.`name`,
  `shop_goods`.`id`           AS `goods_id`,
  `shop_goods`.`id`           AS `id`,
  `shop_goods`.`cover_path`,
  `shop_goods`.`description`,
  `shop_goods`.`status`,
  `shop_goods`.`unit_id`,
  `shop_goods`.`sku_id`,
  `shop_user_collection`.`user_id`,
  `shop_user_collection`.`type`,
  `shop_user_collection`.`type_id`,
  (SELECT min(price)
   FROM shop_product_area
   WHERE id = shop_product_area.id AND shop_product_area.area_id = {$this->select['area_id']}
   LIMIT 0, 1) as `price`,
  `shop_user_collection`.`id` AS `collection_id`"
        ))->distinct();

        return $model;
    }
}