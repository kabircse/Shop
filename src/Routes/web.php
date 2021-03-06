<?php

/**
 * Public Shop Routes.
 */
if (\Illuminate\Support\Facades\Schema::hasTable('laralum_shop_settings')) {
    $public_prefix = \Laralum\Shop\Models\Settings::first()->public_prefix;
} else {
    $public_prefix = 'shop';
}
Route::group([
        'middleware' => ['web', 'laralum.base'],
        'prefix'     => $public_prefix,
        'namespace'  => 'Laralum\Shop\Controllers',
        'as'         => 'laralum_public::',
    ], function () {
        Route::get('/cart', 'ShopController@cart')->name('shop.cart');
        Route::post('/cart/checkout', 'ShopController@checkout')->name('shop.cart.checkout')->middleware('auth');
        Route::post('/cart/add/{item}', 'ShopController@addItem')->name('shop.cart.add');
        Route::post('/cart/remove/{item}', 'ShopController@removeItem')->name('shop.cart.remove');
        Route::get('/item/{item}', 'ShopController@item')->name('shop.item');
        Route::get('/orders', 'ShopController@orders')->name('shop.orders')->middleware('auth');
        Route::get('/orders/{order}', 'ShopController@order')->name('shop.order')->middleware('auth');
        Route::get('/{category?}', 'ShopController@index')->name('shop.index');
    });

/*
 * Laralum Shop Routes
 */
Route::group([
        'middleware' => ['web', 'laralum.base', 'laralum.auth'],
        'prefix'     => config('laralum.settings.base_url'),
        'namespace'  => 'Laralum\Shop\Controllers',
        'as'         => 'laralum::shop.',
    ], function () {
        Route::get('/shop', 'StatisticsController@index')->name('index');
        Route::match(['GET', 'POST'], 'shop/filter/{number?}', 'StatisticsController@filter')->name('index.filter');

        Route::post('/shop/settings', 'SettingsController@update')->name('settings.update');

        // Shop Categories
        Route::group(['middleware' => 'can:access,Laralum\Shop\Models\Category'], function () {
            Route::get('shop/category/{category}/delete', 'CategoriesController@confirmDelete')->name('category.delete');
            Route::resource('shop/category', 'CategoriesController');
        });

        // Shop Orders
        Route::group(['middleware' => 'can:access,Laralum\Shop\Models\Order'], function () {
            Route::match(['GET', 'POST'], 'shop/order/filter/{status?}', 'OrdersController@filter')->name('order.filter');
            Route::post('shop/order/{order}/status', 'OrdersController@status')->name('order.status');
            Route::resource('shop/order', 'OrdersController', ['only' => ['index', 'show']]);
        });

        // Shop Items
        Route::group(['middleware' => 'can:access,Laralum\Shop\Models\Item'], function () {
            Route::get('shop/item/{item}/delete', 'ItemsController@confirmDelete')->name('item.delete');
            Route::resource('shop/item', 'ItemsController');
        });

        // Shop Status
        Route::group(['middleware' => 'can:access,Laralum\Shop\Models\Item'], function () {
            Route::get('shop/status/{status}/delete', 'StatusController@confirmDelete')->name('status.delete');
            Route::resource('shop/status', 'StatusController');
        });
    });
