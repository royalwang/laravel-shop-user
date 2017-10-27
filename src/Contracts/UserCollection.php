<?php
/**
 * Created by PhpStorm.
 * User: coffeekizoku
 * Date: 2017/10/18
 * Time: 11:26
 */

namespace SimpleShop\User\Contracts;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use SimpleShop\Commons\Contracts\CrudContract;

interface UserCollection extends CrudContract
{
    /**
     * 检查是否关注了
     *
     * @param $id
     * @param $type
     *
     * @return bool
     */
    public function check($id): bool;

    /**
     * 撤销
     *
     * @param  int $id 商品的id
     * @param int  $type
     *
     * @return bool
     */
    public function undo($id): bool;

    /**
     *
     *
     * @param  int   $id
     * @param int $type
     *
     * @return mixed
     */
    public function get($id);
}