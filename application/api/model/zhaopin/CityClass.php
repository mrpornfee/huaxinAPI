<?php
/**
 * Created by PhpStorm.
 * User: 93709
 * Date: 2020/4/26
 * Time: 13:57
 */

namespace app\api\model\zhaopin;
use think\Model;

class CityClass extends  Model
{
    protected $pk='id';
    protected $name='city_class';

    public static function getFirstDes(){
        $res=self::where(['keyid'=>0,'display'=>1])->field('id,name')->order('sort asc')->select();
        return myJson('1','success',$res);
    }

    public static function getNextDes($id){
        $res=self::where(['keyid'=>$id,'display'=>1])->field('id,name')->order('sort asc')->select();
        return myJson('1','success',$res);
    }
}