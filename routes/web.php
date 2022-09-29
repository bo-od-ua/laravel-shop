<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', 'IndexController')->name('index');

Route::get('/catalog/index', 'CatalogController@index')->name('catalog.index');
Route::get('/catalog/category/{slug}', 'CatalogController@category')->name('catalog.category');
Route::get('/catalog/brand/{slug}', 'CatalogController@brand')->name('catalog.brand');
Route::get('/catalog/product/{slug}', 'CatalogController@product')->name('catalog.product');

Route::get('/basket/index', 'BasketController@index')->name('basket.index');
Route::get('/basket/checkout', 'BasketController@checkout')->name('basket.checkout');
Route::get('/basket/success', 'BasketController@success')->name('basket.success');
Route::post('/basket/add/{id}', 'BasketController@add')
    ->where('id', '[0-9]+')
    ->name('basket.add');
Route::post('/basket/plus/{id}', 'BasketController@plus')
    ->where('id', '[0-9]+')
    ->name('basket.plus');
Route::post('/basket/minus/{id}', 'BasketController@minus')
    ->where('id', '[0-9]+')
    ->name('basket.minus');
Route::post('/basket/remove/{id}', 'BasketController@remove')
    ->where('id', '[0-9]+')
    ->name('basket.remove');
Route::post('/basket/clear', 'BasketController@clear')->name('basket.clear');
Route::post('/basket/saveorder', 'BasketController@saveOrder')->name('basket.saveorder');

Route::name('user.')->prefix('user')->group(function(){
    Auth::routes();
});

Route::group([
    'as'=> 'user.',
    'prefix'=> 'user',
    'middleware'=> ['auth']
], function (){
    Route::get('index', 'UserController@index')->name('index');
//    Route::resource('profile', 'ProfileController');
//    Route::get('order', 'OrderController@index')->name('order.index');
//    Route::get('order/{order}', 'OrderController@show')->name('order.show');
});

Route::group([
    'as'=> 'admin.',
    'prefix'=> 'admin',
    'namespace'=> 'Admin',
    'middleware'=> ['auth', 'admin']
], function (){
    Route::get('index', 'IndexController')->name('index');
    Route::resource('category', 'CategoryController');
});
//Route::get('/home', 'HomeController@index')->name('home');
