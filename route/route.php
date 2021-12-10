<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::rest('create', ['GET', '/add', 'add']);

Route::resource('brand', 'admin/Brand')->exspet([])->allowCorssDomain();

Route::resource('cate', 'admin/Cate')->exspet([])->allowCorssDomain();

Route::resource('article', 'admin/Article')->exspet([])->allowCorssDomain();

Route::get('article/imglist', 'admin/Article/imgList');
Route::get('article/imgDownload', 'admin/Article/imgDownload');
Route::get('article/imgDelete', 'admin/Article/imgDelete');
Route::get('article/imgDeleteAll', 'admin/Article/imgDeleteAll');

Route::resource('link', 'admin/Link')->exspet([])->allowCorssDomain();

Route::resource('conf', 'admin/Conf')->exspet([])->allowCorssDomain();

Route::get('conf/confList', 'admin/Conf/confList');
Route::post('conf/confSave', 'admin/Conf/confSave');

Route::resource('category', 'admin/Category')->exspet([])->allowCorssDomain();

Route::resource('type', 'admin/Type')->exspet([])->allowCorssDomain();

Route::get('attr/getAttr', 'admin/Attr/getAttr');

Route::resource('attr', 'admin/Attr')->exspet([])->allowCorssDomain();

Route::resource('goods', 'admin/Goods')->exspet([])->allowCorssDomain();

Route::resource('memberlevel', 'admin/MemberLevel')->exspet([])->allowCorssDomain();

Route::resource('order', 'admin/Order')->only(['read'])->allowCorssDomain();
Route::get('order/orderselect', 'admin/Order/orderSelect');
Route::get('order/export', 'admin/Order/export');


Route::resource('nav', 'admin/Nav')->exspet([])->allowCorssDomain();
Route::resource('recpos', 'admin/Recpos')->exspet([])->allowCorssDomain();
Route::resource('categorywords', 'admin/CategoryWords')->exspet([])->allowCorssDomain();
Route::resource('categorybrands', 'admin/CategoryBrands')->exspet([])->allowCorssDomain();
Route::resource('categoryad', 'admin/CategoryAd')->exspet([])->allowCorssDomain();
Route::resource('filter', 'admin/Filter')->exspet([])->allowCorssDomain();

Route::resource('alternate', 'admin/Alternate')->exspet([])->allowCorssDomain();


Route::get('goods/product/:id', 'admin/Goods/product');
Route::get('goods/productSubmit/:id', 'admin/Goods/productSubmit');
Route::get('goods/deletephoto/:id', 'admin/Goods/deletePhoto');
Route::get('goods/getgoodsattr/:id', 'admin/Goods/getGoodsAttr');
Route::get('goods/deleteattr/:id', 'admin/Goods/deleteAttr');

Route::get('filter/getfiltervalues', 'admin/Filter/getFilterValues');


Route::get('index/cate/:id', "index/Cate/Index");
Route::get('index/article/:id', "index/Article/Index");
Route::get('index/goods/getproduct', "index/Goods/getProduct");
Route::get('index/goods/:id', "index/Goods/Index");

Route::get('index/category/getCateInfo', "index/Category/getCateInfo");
Route::get('index/category/getGoodsList', "index/Category/getGoodsList");
Route::get('index/category/search', "index/Category/search");
Route::get('index/category/suggestions', "index/Category/suggestions");

Route::get('index/category/:id', "index/Category/Index");

return [

];
