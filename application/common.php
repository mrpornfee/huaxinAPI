<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function myJson($code,$msg=null,$data=null){
    return json_encode([
        'code'=>$code,
        'msg'=>$msg,
        'data'=>$data,
        ]);
}

/**
 * 获取 post 参数; 在 content_type 为 application/json 时，自动解析 json
 * @return array
 */
 function initPostData()
{
    if (empty($_POST) && false !== strpos($_SERVER['CONTENT_TYPE'], 'application/json')) {
        $content = file_get_contents('php://input');
        $post    = (array)json_decode($content, true);
    } else {
        $post = $_POST;
    }
    return $post;
}