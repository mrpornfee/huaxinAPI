<?php
/**
 * Created by PhpStorm.
 * User: 93709
 * Date: 2020/4/9
 * Time: 10:01
 */

namespace app\api\controller;

use app\api\model\SecretKey;
use app\api\model\Token;
use think\Controller;


class ApplyToken extends Controller
{
    /**制作密钥
     * @return string
     */
       public function makeSecretKey(){
           $postData=initPostData();
           $n=$postData['number'];
           $k=$postData['key'];
           $t=$postData['type'];
           if($t!=0&&$t!=1&&$t!=2&&$t!=3&&$t!=4&&$t!=5)
               return myJson('5','参数type错误');
           if($k!=require dirname(dirname(dirname(__DIR__))).'/public/superman.php')return myJson('4','汝缺少诚意。');
               if($n<=200&&$n>=1) {
               $res=SecretKey::createKeys($n,$t);
               return $res;
           }
           else{
               return myJson('3','最多一次创建200条');
           }

       }

    /**取出（不可回收）
     * @return string
     */
       public function getSecretKey(){
           $postData=initPostData();
            $t=$postData['type'];
            $k=$postData['key'];
             if($t!==0&&$t!==1&&$t!==2&&$t!==3&&$t!==4&&$t!==5)
                 return myJson('2','type格式错误');
             if($k!=require dirname(dirname(dirname(__DIR__))).'/public/superman.php')
                 return myJson('3','汝缺少诚意。');
             $res=SecretKey::takeKey($t);
             return $res;

         }

    /**制作token(一次性)
     * @return string
     */
       public function makeToken(){
           $postData=initPostData();
            $data=[
                'mobile'=>$postData['mobile'],
                'create_time'=>$postData['create_time'],
                'secret_key'=>$postData['secret_key']
            ];
            $res=Token::setToken($data);
            return $res;
        }

    /**查询Token
     * @return string
     */
       public function getToken(){
           $postData=initPostData();
           $data=[
               'mobile'=>$postData['mobile'],
               'secret_key'=>$postData['secret_key']
           ];
           $res=Token::findToken($data);
           return $res;
       }
}