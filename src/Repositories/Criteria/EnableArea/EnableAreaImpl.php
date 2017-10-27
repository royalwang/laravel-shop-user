<?php
/**
 * Created by PhpStorm.
 * User: coffeekizoku
 * Date: 2017/10/23
 * Time: 16:03
 */

namespace SimpleShop\User\Repositories\Criteria\EnableArea;


use SimpleShop\User\Contracts\Criteria;
use SimpleShop\User\Repositories\Criteria\EnableArea\Where as EnableWhere;
use SimpleShop\User\Repositories\Criteria\Where;
use SimpleShop\User\Repositories\Criteria\EnableArea\Join as EnableJoin;
use SimpleShop\User\Repositories\Criteria\Join;
use SimpleShop\User\Repositories\Criteria\EnableArea\Select as EnableSelect;
use SimpleShop\User\Repositories\Criteria\Select;

class EnableAreaImpl implements Criteria
{
    /**
     * where的对象
     *
     * @return \SimpleShop\Repositories\Criteria\Criteria
     */
    public function where(array $search = [])
    {
        $where = new EnableWhere(new Where($search), $search);

        return $where;
    }

    /**
     * join的对象
     *
     * @return \SimpleShop\Repositories\Criteria\Criteria
     */
    public function join(array $join = [])
    {
        $join = new EnableJoin(new Join(), $join);

        return $join;
    }

    /**
     * @return \SimpleShop\Repositories\Criteria\Criteria
     */
    public function select(array $select = [])
    {
        return new EnableSelect(new Select(), $select);
    }
}