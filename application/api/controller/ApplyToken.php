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
       public function makeSecretKey(){
           $n=input('number');
           $k=input('key');
           if($k!=require dirname(dirname(dirname(__DIR__))).'/public/superman.php')return myJson('4','缺少诚意。');
           if($n<=200&&$n>=1) {
               $res=SecretKey::createKeys($n);
               return $res;
           }
           else{
               return myJson('3','最多一次创建200条');
           }

       }

         public function getSecretKey(){


         }

        public function makeToken(){
            $data=[
                'mobile'=>input('mobile'),
                'create_time'=>input('create_time'),
                'type'=>input('type')
            ];
            $res=Token::addApply($data);
            return $res;
        }
}