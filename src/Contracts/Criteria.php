<?php
/**
 * Created by PhpStorm.
 * User: coffeekizoku
 * Date: 2017/10/23
 * Time: 15:48
 */

namespace SimpleShop\User\Contracts;


interface Criteria
{
    /**
     * where的对象
     *
     * @return \SimpleShop\Repositories\Criteria\Criteria
     */
    public function where(array $search = []);

    /**
     * join的对象
     *
     * @return \SimpleShop\Repositories\Criteria\Criteria
     */
    public function join(array $join = []);

    /**
     * @param array $select
     *
     * @return \SimpleShop\Repositories\Criteria\Criteria
     */
    public function select(array $select = []);
}