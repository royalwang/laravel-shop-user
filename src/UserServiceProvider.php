<?php

/*
 * This file is part of the simple-shop/laravel-shop-cart.
 *
 */

namespace SimpleShop\User;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use SimpleShop\Commons\Config;
use SimpleShop\Commons\Exceptions\Exception;
use SimpleShop\User\Contracts\Criteria;
use SimpleShop\User\Https\Controllers\MobileGoodsCollectionController;
use SimpleShop\User\Models\ShopUserCollection;
use SimpleShop\User\Repositories\Criteria\DisableArea\DisableAreaImpl;
use SimpleShop\User\Repositories\Criteria\EnableArea\EnableAreaImpl;
use SimpleShop\User\UserCollectionImpl;
use SimpleShop\User\Contracts\UserCollection as UserCollectionContract;
use App;

/**
 * Service provider for Laravel.
 */
class UserServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Boot the provider.
     */
    public function boot()
    {

    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        //        $this->app->singleton(UserCollectionContract::class, UserCollectionImpl::class);
        $this->registerArea();
        $this->registerGoods();

    }

    /**
     * 注册商品控制器所需的参数
     */
    protected function registerGoods()
    {
        $userCollectionImpl = $this->app->makeWith(UserCollectionImpl::class, ['type' => ShopUserCollection::TYPE_GOODS]);

        $this->app->when(MobileGoodsCollectionController::class)->needs(UserCollectionContract::class)->give(function (
        ) use ($userCollectionImpl) {
            return $userCollectionImpl;
        });
    }

    /**
     * 注册是否要启用区域
     */
    protected function registerArea()
    {
        /** @var Config $config */
        $config = App::make(Config::class);
        switch ($config->getConfig('shop_product_area')) {
            case '1':
                $this->app->singleton(Criteria::class, EnableAreaImpl::class);
                break;
            case '0':
                $this->app->singleton(Criteria::class, DisableAreaImpl::class);
                break;
            default:
                throw new Exception('参数错误');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Criteria::class, UserCollectionContract::class, MobileGoodsCollectionController::class];
    }
}
