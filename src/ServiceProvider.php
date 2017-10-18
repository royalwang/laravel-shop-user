<?php

/*
 * This file is part of the simple-shop/laravel-shop-cart.
 *
 */

namespace SimpleShop\Cart;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use SimpleShop\Cart\CookieCart\Cart as CookieCart;
use SimpleShop\Cart\UserCart\Cart as UserCart;
use Illuminate\Support\Facades\Auth;

/**
 * Service provider for Laravel.
 */
class ServiceProvider extends LaravelServiceProvider
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
        //
    }

    /**
     * Register the service provider.
     */
    public function register()
    {

        $this->app->singleton('cart', function ($app) {
            $cookieCart = new CookieCart($app['session'], $app['events']);
            if(!auth()->check()) {
                return $cookieCart;
            }

            $userCart = new UserCart(auth()->id(), $app['events']);
            $this->dealLoginChat($cookieCart,$userCart);
            return $userCart;

        });
    }

    public function dealLoginChat($cookieCart,$userCart){
        if($cookieCart->isEmpty()){
           return true;
        }
        $aCart = $cookieCart->all();
        foreach($aCart as $item) {
            $userCart->add($item->goods_id,$item->name,$item->quantity,$item->price);
        }
        $cookieCart->clean();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [CookieCart::class, 'cart'];
    }
}
