<?php
/**
 *------------------------------------------------------
 * UserAddressController.php
 *------------------------------------------------------
 *
 * @author    qqiu@qq.com
 * @version   V1.0
 *
 */

namespace SimpleShop\User\Https\Controllers;

use SimpleShop\Commons\Https\Controllers\Controller;
use Illuminate\Http\Request;
use SimpleShop\Commons\Utils\ReturnJson;
use SimpleShop\User\UserAddress;

class UserAddressController extends Controller
{
    /**
     * @var UserAddress
     */
    private $_service;

    /**
     * UserAddressController constructor.
     * @param UserAddress $userAddressService
     */
    public function __construct(UserAddress $userAddressService)
    {
        //TODO PostMan 测试用
        if((env('APP_ENV') == 'local') && env("IS_POSTMAN")){
            auth()->loginUsingId(0);
        }
        $this->_service = $userAddressService;
    }

    /**
     * 获取所有
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $data = $this->_service->getAll();
        return ReturnJson::success($data);
    }

    /**
     * 获取详情
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $data = $this->_service->detail($id);
        return ReturnJson::success($data);
    }

    /**
     * 更新
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $this->_service->update( $id,$request->all());
        return ReturnJson::success();
    }

    /**
     * 增添
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $resData = $this->_service->add($request->all());
        return ReturnJson::success($resData);
    }

    /**
     * 删除
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->_service->remove($id);
        return ReturnJson::success();
    }

    /**
     * 设置默认收货地址
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setDefault($id)
    {
        $this->_service->setDefault($id);
        return ReturnJson::success();
    }

}
