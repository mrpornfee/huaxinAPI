<?php
/**
 * Created by PhpStorm.
 * User: 93709
 * Date: 2020/4/10
 * Time: 18:42
 */

namespace app\api\model\zhaopin;
use think\Model;

class Company extends Model
{
    protected $pk='uid';
    protected $name='company';

    public static function getComInfo(int $uid){
        $res=self::where(['uid'=>$uid])->field('name as com_name,pr,mun,provinceid as com_provinceid,logo as com_logo,r_status,welfare')->find()->toArray();
        $rat=CompanyStatis::where(['uid'=>$uid])->field('rating')->find()['rating']?:0;
        if($res) {
            $res['rating']=$rat;
            return ['com_info'=>$res];
        }
        else return myJson('102','No company  has been found');
    }
}