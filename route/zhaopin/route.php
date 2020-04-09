<?php
/**
 * Created by PhpStorm.
 * User: 93709
 * Date: 2020/4/7
 * Time: 16:47
 */

Route::get('zhaopin/api','api/zhaopin.index/test');
Route::post('api/make_token','api/apply_token/makeToken');
Route::get('api/make_secret_key/:number/:key','api/apply_token/makeSecretKey');