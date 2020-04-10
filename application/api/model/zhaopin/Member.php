<?php
/**
 * Created by PhpStorm.
 * User: 93709
 * Date: 2020/4/10
 * Time: 18:13
 */

namespace app\api\model\zhaopin;

use think\Model;

class Member extends Model
{
    protected $pk='uid';

    protected $name='member';

    public static function getUid($mobile){
        $res=self::where('moblie',$mobile)->find()['uid'];
        if($res) return $res;
        else return myJson('101','No access to publish job because you are not in member of user list.');
    }
}