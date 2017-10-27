<?php
/**
 * Created by PhpStorm.
 * User: jiangzhiheng
 * Date: 2017/10/23
 * Time: 11:05
 */

namespace SimpleShop\User\Https\Controllers;


use SimpleShop\Commons\Https\Controllers\Controller;
use SimpleShop\User\UserImpl;
use LWJ\Services\Oms\OmsUser;
use SimpleShop\Commons\Exceptions\LoginException;
use SimpleShop\Commons\Exceptions\ResourceNotFindException;
use SimpleShop\User\Http\Requests\ListRequest;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    private $service;

    /**
     * DemandController constructor.
     */
    public function __construct(UserImpl $userImpl)
    {
        $this->service = $userImpl;
    }

    /**
     * 列表
     *
     * @param ListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    function lists(ListRequest $request)
    {
        $rules = [
            'user_name'     => ['sometimes', 'string'],
            'user_mobile'   => ['sometimes', 'string'],
            'email'         => ['sometimes', 'string'],
        ];
        $errors = $this->validateField($request->all(), $rules);
        if( !empty($errors) ){
            return $this->fail($errors);
        }

        $list = $this->service->search(
            $request->all(),
            [$request->getParams()['sort'] => $request->getParams()['order']],
            true,
            $request->getParams()['limit']
        );

        return $this->paginate($list);
    }

    /**
     * 用户详情
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail($id)
    {
        $data = $this->service->find($id);
        return $this->success($data);
    }

    /**
     * 设置管理员
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setAdmin($id)
    {
        $data['is_admin'] = 1;
        $res = $this->service->update($id, $data);
        if( $res ){
            return $this->success();
        }else{
            return $this->fail('操作失败');
        }
    }

    /**
     * 取消管理员
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelAdmin($id)
    {
        $data['is_admin'] = 0;
        $res = $this->service->update($id, $data);
        if( $res ){
            return $this->success();
        }else{
            return $this->fail('操作失败');
        }
    }

    /**
     * 创建用户
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $data = $request->all();
        $rules = [
            'userName'          => ['required', 'string'],
            'userMobile'        => ['required', 'string'],
        ];
        $errors = $this->validateField($data, $rules);
        if( !empty($errors) ){
            return $this->fail($errors);
        }

        $res = $this->service->omsRegister($data);
        if( $res ){
            return $this->success();
        }else{
            return $this->fail('操作失败');
        }
    }

    /**
     * 获取当前登录用户
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function current()
    {
        $user = OmsUser::isLogin();

        if (! $user) {
            return $this->success($user);
        }

        throw new LoginException('用户没有登录');
    }

    /**
     * 禁用启用
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable($id)
    {
        $res = $this->service->updateStatus($id);
        if( $res ){
            return $this->success();
        }else{
            return $this->fail('操作失败');
        }
    }

    /**
     * 从OMS获取用户
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getUserFromOMS(Request $request)
    {
        try{
            // 判断是需要搜索啥
            if ($request->has('mobile')) {
                $user = OmsUser::getUserByMobile($request->input('mobile'));
            }

            if ($request->has('email')) {
                $user = OmsUser::getUserByEmail($request->input('email'));
            }

            if (empty($user) || $user['status'] == 500) {
                throw new \Exception('没有找到对应的用户');
            }

            // 将这个数据写入本系统的数据表
            $user = $this->service->OmsCheckUser($user['data']);

            return $this->success($user);

        }catch(\Exception $exception){
            throw new ResourceNotFindException($exception->getMessage(), $exception->getCode());
        }

    }
}