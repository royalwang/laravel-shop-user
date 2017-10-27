<?php
/**
 * Created by PhpStorm.
 * User: coffeekizoku
 * Date: 2017/10/18
 * Time: 11:42
 */

namespace SimpleShop\User;


use SimpleShop\Commodity\Models\ShopGoodsProductModel;
use SimpleShop\User\Contracts\Criteria;
use SimpleShop\User\Contracts\UserCollection;
use SimpleShop\User\Repositories\Criteria\BaseWhere;
use SimpleShop\User\Repositories\Criteria\Join;
use SimpleShop\User\Repositories\Criteria\Where;
use SimpleShop\User\Repositories\UserCollectionRepository;
use SimpleShop\Commons\Exceptions\LoginException;
use Auth;
use DB;

class UserCollectionImpl implements UserCollection
{
    /**
     * userCollection的仓库
     *
     * @var UserCollectionRepository
     */
    private $repo;

    /**
     * 点赞对应的类别
     *
     * @var int
     */
    protected $type;

    /**
     * @var Criteria
     */
    protected $criteria;

    public function __construct(UserCollectionRepository $userCollectionRepository, Criteria $criteria, $type)
    {
        $this->repo = $userCollectionRepository;
        $this->criteria = $criteria;
        $this->type = $type;
    }

    /**
     * 用户的get方法
     */
    protected function getUser()
    {
        $user = Auth::user();
        if (! $user) {
            throw new LoginException();
        }

        return $user;
    }

    /**
     * 展示
     *
     * @param $id
     *
     * @return mixed
     */
    public function show($id)
    {
        // TODO: Implement update() method.
    }

    /**
     * 修改
     *
     * @param int   $id sku的id
     * @param array $data
     *
     * @return mixed
     */
    public function update($id, array $data)
    {
        // TODO: Implement update() method.
    }

    /**
     * 添加
     *
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data)
    {
        // 检查是否已经收藏了
        $status = $this->check($data['id'], $data['type']);
        // 如果是,直接返回true
        if ($status) {
            return true;
        }
        // 如果是否,进行插入,再返回true
        $this->repo->create([
            'user_id' => $this->getUser()->id,
            'type'    => $data['type'],
            'type_id' => $data['id'],
        ]);

        return true;
    }

    /**
     * 清掉
     *
     * @return void
     */
    public function delete($id)
    {
        // 获取
        $this->repo->delete($id);
    }

    /**
     * 获取
     *
     * @param array $search
     * @param int   $limit
     * @param int   $page
     * @param array $colums
     * @param array $orderBy
     *
     * @return mixed
     */
    public function getList(
        array $search = [],
        int $limit = 20,
        int $page = 1,
        array $columns = ['*'],
        array $orderBy = ['id' => 'desc']
    ) {
        $list= $this->repo->pushCriteria($this->criteria->join($search))
            ->pushCriteria($this->criteria->where($search))
            ->pushCriteria($this->criteria->select($search))
            ->paginate($limit,
                $columns, $page);

        return $list;
    }

    /**
     * 检查是否已经关注
     *
     * @param $id
     *
     * @return bool
     */
    public function check($id): bool
    {
        $count = $this->get($id);

        return is_null($count) ? false : true;
    }

    /**
     * 撤销
     *
     * @param int $id
     * @param int $type
     */
    public function undo($id): bool
    {
        $this->repo->pushCriteria($this->criteria->where([
            'user_id' => $this->getUser()->id,
            'type'    => $this->type,
            'type_id' => $id,
        ]))->delete();

        return true;
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function get($id)
    {
        return $this->repo->pushCriteria($this->criteria->where([
            'user_id' => $this->getUser()->id,
            'type'    => $this->type,
            'type_id' => $id,
        ]))->first();
    }

    public function setDefaultSku($list)
    {
        $skuIds = $list->pluck('sku_id')->toArray();
        $result = ShopGoodsProductModel::whereIn('id', $skuIds)->get();


        foreach ($result as $item) {
            foreach ($list as $value) {
                if ($value->goods_id === $item->goods_id) {
                    $value->sku_info = $item;
                }
            }
        }

        return $list;

    }
}