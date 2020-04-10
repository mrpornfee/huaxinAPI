<?php
/**
 * Created by PhpStorm.
 * User: 93709
 * Date: 2020/4/7
 * Time: 16:47
 */

Route::post('api/make_token','api/apply_token/makeToken');
Route::post('api/get_token','api/apply_token/getToken');
Route::post('api/make_secret_key','api/apply_token/makeSecretKey');
Route::post('api/get_secret_key','api/apply_token/getSecretKey');