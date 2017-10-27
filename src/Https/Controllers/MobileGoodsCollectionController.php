<?php
/**
 * Created by PhpStorm.
 * User: coffeekizoku
 * Date: 2017/10/18
 * Time: 11:43
 */

namespace SimpleShop\User\Https\Controllers;


use Illuminate\Http\Request;
use SimpleShop\Commons\Https\Controllers\Controller;
use SimpleShop\Commons\Utils\ReturnJson;
use SimpleShop\User\Contracts\UserCollection as UserCollectionContract;

class MobileGoodsCollectionController extends Controller
{
    /**
     * @var UserCollectionImpl
     */
    private $userCollection;

    public function __construct(UserCollectionContract $userCollectionImpl)
    {
        $this->userCollection = $userCollectionImpl;
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable($id)
    {
        $this->userCollection->create(['id' => $id, 'type' => 1]);

        return ReturnJson::success();
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable($id)
    {
        $this->userCollection->undo($id);

        return ReturnJson::success();
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function check($id)
    {
        $bool = $this->userCollection->check($id);

        return ReturnJson::success($bool);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        $data = $this->userCollection->getList(['type' => 1, 'cate_id' => $request->input('cate_id'), 'area_id' => $request->input('area_id')]);
        $data = $this->userCollection->setDefaultSku($data);
        return ReturnJson::paginate($data);
    }
}