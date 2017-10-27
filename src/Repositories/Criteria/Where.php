<?php
/**
 * Created by PhpStorm.
 * User: coffeekizoku
 * Date: 2017/10/18
 * Time: 16:21
 */

namespace SimpleShop\User\Repositories\Criteria;


use SimpleShop\Repositories\Contracts\RepositoryInterface as Repository;
use SimpleShop\Repositories\Criteria\Criteria;
use SimpleShop\Cate\CateImpl;
use App;

class Where extends Criteria
{
    /**
     * @var array
     */
    protected $search;

    public function __construct(array $search)
    {
        $this->search = $search;
    }

    /**
     * @param            $model
     * @param Repository $repository
     *
     * @return mixed
     */
    public function apply($model, Repository $repository)
    {
        if (isset($this->search['user_id'])) {
            $model = $model->where('shop_user_collection.user_id', $this->search['user_id']);
        }

        if (isset($this->search['type'])) {
            $model = $model->where('shop_user_collection.type', $this->search['type']);
        }

        if (isset($this->search['type_id'])) {
            $model = $model->where('shop_user_collection.type_id', $this->search['type_id']);
        }

        if (! empty($this->search['cate_id'])) {
            $cate = App::make(CateImpl::class);
            $cateIds = $cate->getChildIds($this->search['cate_id']);
            $model = $model->whereIn('shop_goods.cate_id', $cateIds);
        }

        return $model;
    }
}