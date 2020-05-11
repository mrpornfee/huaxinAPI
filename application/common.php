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

function initDeleteData()
{
    if (empty($_DELETE) && false !== strpos($_SERVER['CONTENT_TYPE'], 'application/json')) {
        $content = file_get_contents('php://input');
        $delete    = (array)json_decode($content, true);
    } else {
        $delete  = $_DELETE;
    }
    return $delete ;
}

function initPutData()
{
    if (empty($_PUT) && false !== strpos($_SERVER['CONTENT_TYPE'], 'application/json')) {
        $content = file_get_contents('php://input');
        $put    = (array)json_decode($content, true);
    } else {
        $put  = $_PUT;
    }
    return  $put ;
}

//数组验证
function arrayVerify(array $arr,string $str){
    $ak=array_keys($arr);
    $arrTmp=explode(',',$str);
    if(!array_diff($ak,$arrTmp)&&!array_diff($arrTmp,$ak))
        return true;
    else return false;
}

if(!function_exists('curl_request')) {
    /**
     * @param $url 请求的地址
     * @param $pos请求的方式
     * @param $params请求的参数
     * @param $https是否验证http证书  默认不验证http证书
     */
    function curl_request($url, $post = false, $params = [], $https = false, $header = null)
    {
        $headerMode = [
            'Content-Type' => 'application/json;charset=uft-8',
            'Accept' => 'application/json;charset=utf-8',
            'Content-Length' => strlen(json_encode($params))
        ];
        if (!empty($header)) $headerMode = array_merge($headerMode, $header);
        #初始化请求的参数
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headerMode);
        curl_setopt($curl, CURLOPT_HEADER, 0);//返回response头部信息
        curl_setopt($curl, CURLOPT_URL, $url);
        #设置请求选项
        if ($post) {
            #设置发送post请求
            curl_setopt($curl, CURLOPT_POST, true);
            #设置post请求的参数
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        }
        #是否https协议的验证
        if ($https) {
            #禁止从服务器验证客户端本地的数据
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        }
        #发送请求
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($curl);
        if ($res === false) {
            $msg = curl_error($curl);
            return $msg;
        }
        #关闭请求
        curl_close($curl);
        return $res;
    }
}
//签名
 function sign($url)
{
    str_replace("+","%20",$url);
    str_replace("*","%2A",$url);
    str_replace("%7E","~",$url);
    return $url;
}

/**
 * 简单对称加密算法之加密
 * @param String $string 需要加密的字串
 * @param String $skey   加密EKY
 * @return String
 */
function encode($string = '', $skey = 'php')
{
    $strArr   = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value) {
        $key < $strCount && $strArr[$key] .= $value;
    }
    return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
}


/**
 * 简单对称加密算法之解密
 * @param String $string 需要解密的字串
 * @param String $skey   解密KEY
 * @return String
 */
function decode($string = '', $skey = 'php')
{
    $strArr   = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value) {
        $key <= $strCount && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
    }
    return base64_decode(join('', $strArr));
}
