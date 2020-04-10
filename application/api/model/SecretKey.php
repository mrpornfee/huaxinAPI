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

    public static function createKeys(int $n,int $t){
            $res=self::createString($n);
            $data=[];
            for($i=0;$i<$n;$i++){
                if($t==-1){
                    $type=rand(0,3);
                }else{
                    $type=$t;
                }
                array_push($data,['secret_key'=>$res[$i],'type'=>$type]);
            }
           $a=self::insertAll($data);
            if($a)
            return myJson('1','创建api密钥成功');
            else return myJson('2','创建失败');
    }


    public static function takeKey($t){
        $res=self::where(['type'=>$t,'is_used'=>0])->field('id,secret_key')->find();
        if($res) {
            $back=self::where('id',$res['id'])->update(['is_used'=>1]);
            if($back){
            return myJson('1','获取密钥成功',['secert_key'=>$res["secret_key"]]);
            }
            else return myJson('6','取密钥的人过多，稍后尝试');
        }
        return myJson('4','获取密钥失败,仓库密钥不足/(ㄒoㄒ)/~~');
    }
}