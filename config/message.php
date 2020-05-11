<?php
return [
    "Host"=>"dysmsapi.aliyuncs.com",
    /*
        接收短信的手机号码。
    格式：
    国内短信：11位手机号码，例如15951955195。
    国际/港澳台消息：国际区号+号码，例如85200000000。
    支持对多个手机号码发送短信，手机号码之间以英文逗号（,）分隔。上限为1000个手机号码。批量调用相对于单条调用及时性稍有延迟。*/
   // 'PhoneNmubers' => '18512191879',
    /*短信签名名称。请在控制台签名管理页面签名名称一列查看。*/
    'SignName' => '华莘',
    /*主账号AccessKey的ID。*/
    'AccessKeyId' => 'LTAI4G2EGhJLgkscxia96vR8',

    'AccessKeySecret'=>'asnuEl3iYfVeNrvOUI3MGl0OTJUZHhtcHN5OE0wQTJocjN6',

    "decodeKey"=>"sulifer",

    "Product"=>"Dysmsapi",

    /*	API 的名称。*/
    'Action' => 'SendSms',

    /*返回参数的语言类型。取值范围：json | xml。默认值：json。*/
    'Format'=>'JSON',
    /*API支持的RegionID，如短信API的值为：cn-hangzhou。*/
    'RegionId'=>'cn-hangzhou',

    /*API 的版本号，格式为 YYYY-MM-DD。取值范围：2017-05-25。*/
    'Version'=>'2017-05-25',

    "MaxSend"=>50
];