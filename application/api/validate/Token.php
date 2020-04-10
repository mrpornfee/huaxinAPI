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
        'mobile'=>'require|mobile|unique:huaxinapi',
        'create_time'=>'require',
        ];

    protected $msg = [
        'mobile.require' => '手机号必须',
        'mobile.mobile'=>'手机号格式错误',
        'create_time.require' => 'create_time必须',
    ];

    protected $scene=[
      'add'=>['mobile','create_time'] ,
    ];
}