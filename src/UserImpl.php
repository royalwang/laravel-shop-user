<?php
/**
 * Created by PhpStorm.
 * User: coffeekizoku
 * Date: 2017/10/23
 * Time: 11:11
 */

namespace SimpleShop\User;


use SimpleShop\User\Models\UserInfo;
use LWJ\Services\Oms\OmsUser;

class UserImpl
{
    private $model;

    public function __construct(UserInfo $userInfo)
    {
        $this->model = $userInfo;
    }

    /**
     * 搜索
     *
     * @param array $search
     * @param array $orderBy
     * @param bool  $isPage
     * @param int   $pageSize
     *
     * @return RemakeModel
     */
    public function search(array $search, array $orderBy = [], $isPage = true, $pageSize = 10)
    {
        $currentQuery = $this->model;

        //用户姓名
        if (isset($search['name']) && trim($search['name'])) {
            $currentQuery = $currentQuery->where("name", 'like', '%' . $search['name'] . '%');
        }

        //联系电话
        if (isset($search['mobile']) && trim($search['mobile'])) {
            $currentQuery = $currentQuery->where("mobile", 'like', '%' . $search['mobile'] . '%');
        }

        //邮箱
        if (isset($search['email']) && trim($search['email'])) {
            $currentQuery = $currentQuery->where("email", 'like', '%' . $search['email'] . '%');
        }

        //是否管理员
        if (isset($search['is_admin']) && is_numeric($search['is_admin'])) {
            $currentQuery = $currentQuery->where("is_admin", $search['is_admin']);
        }

        //排序
        if ($orderBy && is_array($orderBy)) {
            foreach ($orderBy as $field => $sort) {
                $currentQuery = $currentQuery->orderBy($field, $sort);
            }
        } else {
            $currentQuery = $currentQuery->orderBy("created_at", 'DESC');
        }

        //是否分页
        if ($isPage) {
            $currentQuery = $currentQuery->paginate($pageSize);
        } else {
            $currentQuery = $currentQuery->get();
        }

        return $currentQuery;
    }

    /**
     * 查询
     *
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * 更新
     *
     * @param $id
     * @param $data
     *
     * @return bool
     * @throws \Exception
     */
    public function update($id, $data)
    {
        $resData = $this->model->find($id);
        if (! $resData) {
            throw new \Exception("没有找到要修改的数据");
        }

        return $resData->update($data);
    }

    /**
     * OMS用户注册
     *
     * @param $data
     *
     * @return mixed
     * @throws \Exception
     */
    public function omsRegister($data)
    {
        $registerInfo['mobile'] = $data['userMobile'];
        $registerInfo['name'] = isset($data['userName']) ? $data['userName'] : $data['userMobile'];
        if (isset($data['password'])) {
            $registerInfo['password'] = $data['password'];
        }

        $omsUser = OmsUser::getUserByMobile($registerInfo['mobile']);

        if (! isset($omsUser['data'])) {
            //注册
            $omsUser = OmsUser::register($registerInfo);
            if (! isset($omsUser['data'])) {
                throw new \Exception('用户注册失败' . $omsUser['msg']);
            }
            $omsUser = OmsUser::getUserById($omsUser['data']['user_id']);
            if (! isset($omsUser['data'])) {
                throw new \Exception('获取用户失败');
            }
        }

        // 使用用户提交的姓名
        if ($registerInfo['name'] != $registerInfo['mobile']) {
            $omsUser['data']['name'] = $registerInfo['name'];
        }

        return $this->OmsCheckUser($omsUser['data']);
    }

    /**
     * 检查OMS的用户
     *
     * @param $user
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function OmsCheckUser($user)
    {
        return $this->model->updateOrCreate(['id' => $user['id']],
            [
                'id'     => $user['id'],
                'name'   => isset($user['name']) ? $user['name'] : '',
                'mobile' => isset($user['mobile']) ? $user['mobile'] : '',
                'email'  => isset($user['email']) ? $user['email'] : '',
                'type'   => isset($user['type']) ? $user['type'] : 0,
            ]);

    }

    /**
     * 检查手机号是否被注册
     *
     * @param $mobile
     *
     * @return bool
     */
    public function checkMobile($mobile)
    {
        $omsUser = OmsUser::getUserByMobile($mobile);

        return isset($omsUser['data']) ? true : false;
    }

    /**
     * @param $mobile
     * @param $smsCode
     *
     * @return mixed
     * @throws \Exception
     */
    public function loginMobile($mobile, $smsCode)
    {

        if (session('_authCode') != $smsCode) {
            if (env('APP_ENV') != 'local') {
                throw new \Exception('验证码错误或已失效');
            }
        }

        $omsUser = OmsUser::getUserByMobile($mobile);

        if (! isset($omsUser['data'])) {
            //注册
            $omsUser = OmsUser::registerSimple($mobile);
            if (! isset($omsUser['data'])) {
                throw new \Exception('手机号注册失败' . $omsUser['msg']);
            }
            $omsUser = OmsUser::getUserById($omsUser['data']['user_id']);
            if (! isset($omsUser['data'])) {
                throw new \Exception('获取用户失败');
            }
        }

        $user = $this->OmsCheckUser($omsUser['data']);
        auth()->loginUsingId($user['id']);

        return auth()->user();
    }

    /**
     * 返回超管的数组
     *
     * @return array
     */
    public static function getSuperAdmin()
    {
        $admin = config('user.super_admin_users');

        return explode('|', $admin);
    }

    /**
     * 获取登录用户
     *
     * @return array
     */
    public static function getLoginUser()
    {
        if (! auth()->check()) {
            // 获取用户id
            $user = OmsUser::isLogin();
            if (! $user) {
                throw new LoginException('当前用户没有登录');
            }
            // 将用户写到本系统的USER表中
            $user = (new static())->OmsCheckUser($user);
            auth()->loginUsingId($user['id']);
        } else {
            $user = auth()->user()->toArray();
        }

        return $user;
    }

    /**
     * 将user通过Email写进本系统的表中
     *
     * @param string $mobile
     *
     * @return array|\stdClass
     */
    public static function storeUserByMobile($mobile)
    {
        $omsUser = OmsUser::getUserByMobile($mobile);
        if (! isset($omsUser['data'])) {
            //注册
            $omsUser = OmsUser::registerSimple($mobile);
            if (! isset($omsUser['data'])) {
                throw new ServiceErrorException('手机号注册失败' . $omsUser['msg']);
            }
            $omsUser['data']['id'] = $omsUser['data']['user_id'];
        }
        $data['user_id'] = $omsUser['data']['id'];

        $omsUser = OmsUser::getUserById($data['user_id']);

        if (! isset($omsUser['data'])) {
            throw new ServiceErrorException('获取用户失败');
        }

        $info = app(static::class);

        return $info->OmsCheckUser($omsUser['data']);
    }

    /**
     * 更新状态
     *
     * @param $id
     *
     * @return bool
     */
    public function updateStatus($id)
    {
        $resData = $this->model->find($id);
        if (isset($resData['id'])) {

            if ($resData['status'] == dict()->value('global', 'bool', 'yes')) {
                return $resData->update(['status' => dict()->value('global', 'bool', 'no')]);
            } else {
                return $resData->update(['status' => dict()->value('global', 'bool', 'yes')]);
            }

        }

        return false;
    }
}