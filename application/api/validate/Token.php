<?php
/**
 * Created by PhpStorm.
 * User: 93709
 * Date: 2020/4/9
 * Time: 10:25
 */

namespace app\api\validate;


use think\Validate;

class Token extends Validate
{
    protected $rule=[
        'mobile'=>'require|mobile|unique:',
        'type'=>'require|in:0,1,2,3,4,5',
        'create_time'=>'require',
        ];

    protected $msg = [
        'mobile.require' => '手机号必须',
        'mobile.mobile'=>'手机号格式错误',
        'type.require' => 'type必须',
        'type.in'=>'type格式错误',
        'create_time.require' => 'create_time必须',
    ];

    protected $scene=[
      'add'=>['mobile','type','create_time'] ,
    ];
}