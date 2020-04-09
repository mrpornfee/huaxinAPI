<?php
/**
 * Created by PhpStorm.
 * User: 93709
 * Date: 2020/4/9
 * Time: 10:24
 */

namespace app\api\model;

use think\Model;

class Token extends Model
{   protected $name='huaxinapi';
    protected  $pk='id';

    public static function addApply($data){
        $validate = new \app\api\validate\Token();
        if(!$validate->scene('add')->check($data)){
            return myJson('2',$validate->getError());
        }
        if($data['create_time']+60<time()) return myJson('3','Your application is out of time.');
        ksort($data);
        $str=implode($data);
        $token=md5($str);
        $data['token']=$token;

        self::insert($data);
        return myJson('1','Your token is successfully applied.', $data);
    }
}