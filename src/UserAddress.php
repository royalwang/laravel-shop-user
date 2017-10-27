<?php
/**
 *------------------------------------------------------
 * UserAddressManager.php
 *------------------------------------------------------
 *
 * @author    qqiu@qq.com
 * @version   V1.0
 *
 */

namespace  SimpleShop\User;

use SimpleShop\User\Contracts\UserAddressContract;
use SimpleShop\Commons\Exceptions\DatabaseException;
use SimpleShop\User\Repositories\UserAddressRepository;

class UserAddress implements UserAddressContract
{
    /**
     * @var UserAddressRepository
     */
    protected $userAddressModel;
    protected $userAddressRepository;

    /**
     * UserAddress constructor.
     * @param UserAddressRepository $userAddressRepository
     */
    public function __construct(UserAddressRepository $userAddressRepository)
    {
        $this->userAddressModel = $userAddressRepository->getModel();
        $this->userAddressRepository = $userAddressRepository;
    }

    /**
     * 获取所有
     *
     * @return mixed
     */
    public function getAll()
    {
        return $this->userAddressModel->where("user_id", auth()->id())->get();
    }

    /**
     * 获取详情
     *
     * @param $id
     * @return mixed
     */
    public function detail($id)
    {
        return $this->userAddressModel
            ->where("user_id", auth()->id())
            ->where("id", $id)
            ->first();
    }

    /**
     * 添加
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        $data['user_id'] = auth()->id();
        $resData = $this->userAddressRepository->create($data);
        if ( !$resData ) {
            throw new DatabaseException("添加数据失败");
        }

        // 更新默认地址
        if( isset($data['default']) && $data['default'] ){
            $this->setDefault($resData['id']);
        }
        return $resData;
    }

    /**
     * 更新
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function update($id, $data)
    {
        $resData = $this->userAddressModel
            ->where("user_id", auth()->id())
            ->where("id", $id)
            ->update($data);
        if ( $resData === false ) {
            throw new DatabaseException("更新数据失败");
        }
        // 更新默认地址
        if( isset($data['default']) && $data['default'] ){
            $this->setDefault($id);
        }
        return $resData;
    }

    /**
     * 删除
     *
     * @param $id
     * @return mixed
     */
    public function remove($id)
    {
        $resData = $this->userAddressModel
            ->where("user_id", auth()->id())
            ->where("id", $id)
            ->delete($id);
        if ( $resData === false ) {
            throw new DatabaseException("删除数据失败");
        }
        return $resData;
    }

    /**
     * 设置默认收货地址
     *
     * @param $id
     * @param null $userId
     * @return mixed
     */
    public function setDefault($id, $userId = null)
    {
        $userId = $userId ? $userId : auth()->id();
        $this->userAddressModel->where("user_id", $userId)->update(['default' => 0]);
        $this->userAddressModel->where("user_id", $userId)->where("id", $id)->update(['default' => 1]);
        return true;
    }

}
