<?php
/**
 * Created by PhpStorm.
 * User: coffeekizoku
 * Date: 2017/10/18
 * Time: 11:18
 */

namespace SimpleShop\User\Repositories;


use SimpleShop\Repositories\Eloquent\Repository;

class UserCollectionRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'SimpleShop\User\Models\ShopUserCollection';
    }
}