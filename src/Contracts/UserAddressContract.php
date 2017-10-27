<?php
/**
 *------------------------------------------------------
 * UserAddressContract.php
 *------------------------------------------------------
 *
 * @author    qqiu@qq.com
 * @version   V1.0
 *
 */

namespace SimpleShop\User\Contracts;

interface UserAddressContract
{
    /**
     * @return mixed
     */
    public function getAll();

    /**
     * @param $id
     * @return mixed
     */
    public function detail($id);

    /**
     * @param $data
     * @return mixed
     */
    public function add($data);

    /**
     * @param $data
     * @param $id
     * @return mixed
     */
    public function update($id, $data);

    /**
     * @param $id
     * @return mixed
     */
    public function remove($id);

    /**
     * @param $id
     * @param null $userId
     * @return mixed
     */
    public function setDefault($id, $userId = null);

}