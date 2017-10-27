<?php
/**
 *------------------------------------------------------
 * UserAddressRepository.php
 *------------------------------------------------------
 *
 * @author    qqiu@qq.com
 * @version   V1.0
 *
 */

namespace SimpleShop\User\Repositories;

use SimpleShop\User\Models\ShopUserAddressModel;
use SimpleShop\Repositories\Eloquent\Repository;

/**
 * Class UserAddressRepository
 * @package SimpleShop\Commons\Repositories
 */
class UserAddressRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return ShopUserAddressModel::class;
    }

    /**
     * Get Model
     *
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

}
