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
{
    protected  $name='huaxinapi';
    protected  $pk='id';

    /**设置Token
     * @param $data
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function setToken($data){
        $validate = new \app\api\validate\Token();
        if(!$validate->scene('add')->check($data)){
            return myJson('2',$validate->getError());
        }
        if($data['create_time']+60<time()) return myJson('3','Your application is out of time.');
        if(!$data['secret_key']) return myJson('4','Please input your secret key');
        $secretKey=model('SecretKey')->where('secret_key',$data['secret_key'])->find();
        ksort($data);
        $str=implode($data);
        $token=md5($str);
        $data['token']=$token;
        $data['secret_key_id']=$secretKey['id'];
        $data['type']=$secretKey['type'];
        unset($data['secret_key']);
        self::insert($data);
        return myJson('1','Your token is successfully applied.', $data);
    }

    /**获取Token
     * @param $data
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function findToken($data){
        if(!$data['mobile'])
            return myJson('2','No mobile input');
        if(!$data['secret_key'])
            return myJson('3','No secret_key input');
        $arr=model('SecretKey')->where(['secret_key'=>$data['secret_key'],'is_used'=>1])->find();
        if(!$arr) return myJson('4','Incorrect secret key');
        $info=self::where('secret_key_id',$arr['id'])->field('mobile,token')->find();
        if($info['mobile']!=$data['mobile'])
            return myJson('5','That is not your secret key');
        return myJson('1','Successfully get your token',$info);

    }


    public static function requestVerify( $token,$mobile,$type){
        $res=self::where(['mobile'=>$mobile,'token'=>$token,'type'=>$type])->find();
        if($res) return true;
        else return false;
    }
}