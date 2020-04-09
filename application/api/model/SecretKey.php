<?php
/**
 * Created by PhpStorm.
 * User: 93709
 * Date: 2020/4/9
 * Time: 20:20
 */

namespace app\api\model;
use think\Model;

class SecretKey extends Model
{
    protected $pk='id';
    protected $name='huaxinapi_available';

    public static function createString(int $n){
        $rand="";
        for($j=0;$j<$n;$j++) {
            for ($i = 0; $i < 50; $i++) {
                $rand = $rand . chr(mt_rand(33, 126));
            }
            $rands[$j]=md5($j.$rand);
        }
        return $rands;
    }

    public static function createKeys(int $n){
            $res=self::createString($n);
            $data=[];
            for($i=0;$i<$n;$i++){
                array_push($data,['secret_key'=>$res[$i]]);
            }
           $a=self::insertAll($data);
            if($a)
            return myJson('1','创建api密钥成功');
            else return myJson('2','创建失败');
    }
}