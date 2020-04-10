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
require_once __DIR__.'/zhaopin/route.php';

Route::post('api/make_token','api/apply_token/makeToken');
Route::post('api/get_token','api/apply_token/getToken');
Route::post('api/make_secret_key','api/apply_token/makeSecretKey');
Route::post('api/get_secret_key','api/apply_token/getSecretKey');

return [

];