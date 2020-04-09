<?php
/**
 * Created by PhpStorm.
 * User: 93709
 * Date: 2020/4/9
 * Time: 10:01
 */

namespace app\api\controller;

use app\api\model\Token;
use think\Controller;

class ApplyToken extends Controller
{
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