<?php
/**
 * Created by PhpStorm.
 * User: coffeekizoku
 * Date: 2017/10/23
 * Time: 16:05
 */

namespace SimpleShop\User\Repositories\Criteria\DisableArea;


use SimpleShop\User\Contracts\Criteria;
use SimpleShop\User\Repositories\Criteria\Join;
use SimpleShop\User\Repositories\Criteria\Select;
use SimpleShop\User\Repositories\Criteria\Where;

class DisableAreaImpl implements Criteria
{
    /**
     * where的对象
     *
     * @return mixed
     */
    public function where(array $search = [])
    {
        return new Where($search);
    }

    /**
     * join的对象
     *
     * @return mixed
     */
    public function join(array $join = [])
    {
        return new Join();
    }

    /**
     * @return \SimpleShop\Repositories\Criteria\Criteria
     */
    public function select(array $select = [])
    {
        return new Select();
    }


}