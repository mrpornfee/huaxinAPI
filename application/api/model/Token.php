<?php
/**
 * Created by PhpStorm.
 * User: 93709
 * Date: 2020/4/9
 * Time: 10:24
 */

namespace app\api\model;


use think\Config;
use think\Model;

class Token extends Model
{
    protected $table='phpyun_huaxinapi';

    protected  $pk='id';

    protected $connection = [
        // 数据库类型
        'type'        => 'mysql',
        // 服务器地址
        'hostname'    => '127.0.0.1',
        // 数据库名
        'database'    => 'huachuangzhaopin',
        // 数据库用户名
        'username'    => 'root',
        // 数据库密码
        'password'    => 'root',
        // 数据库编码默认采用utf8
        'charset'     => 'utf8',
        // 数据库表前缀
        'prefix'      => 'phpyun_',
        // 数据库调试模式
        'debug'       => false,
    ];

    public static function addApply($data){
        $validate = new \app\api\validate\Token();
        if(!$validate->scene('add')->check($data)){
            return myJson('2',$validate->getError());
        }
        if($data['create_time']+60<time()) return myJson('3','Your application is out of time.');
        $str=implode('',sort($data));
        $token=md5($str);
        $data['token']=$token;
        $tokenModel=model('Token');
        $tokenModel->data($data);
        $tokenModel->save();
        return myJson('1','Your token is successfully applied.', $data);
    }
}