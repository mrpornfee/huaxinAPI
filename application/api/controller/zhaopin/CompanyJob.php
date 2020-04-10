<?php
/**
 * Created by PhpStorm.
 * User: 93709
 * Date: 2020/4/8
 * Time: 16:05
 */

namespace app\api\controller\zhaopin;


use app\api\model\Token;
use think\Controller;
use think\facade\Request;
use app\api\model\zhaopin\Member;
class CompanyJob extends  Controller
{
    private static $token;
    private static $mobile;
    private static $type;
    private static $result;

    public function __construct(){
        parent::__construct();
       $token=Request::header('Token');
       $mobile=Request::header('Mobile');
       $type=Request::header('Type');
       $res=Token::requestVerify($token,$mobile,$type);
       if($res){
           self::$mobile=$mobile;
           self::$token=$token;
           self::$type=$type;
       }else{
           self::$result=myJson('-1','Fail to varify your infomation.');
       }
    }

    public function publishJob(){
        if(self::$result) return self::$result;
        $postData=initPostData();
       switch(self::$type) {
           case 0:
             $res=$this->publishJobAdmin($postData);
             break;
           case 1:
             $res= $this->publishJobUser($postData);
             break;
           case 2:
               $res= $this->publishJobUser($postData);
               break;
           case 3:
               $res= $this->publishJobUser($postData);
               break;
           case 4:
               $res= $this->publishJobUser($postData);
               break;
           case 5:
               $res= $this->publishJobUser($postData);
               break;
       }
       return $res;
    }

    private  function publishJobAdmin($postData){

    }

    private  function publishJobUser($postData){
           $uid=Member::getUid(self::$mobile);
           $data=[
             'uid'=>$uid,
             'name'=>$postData['name'],

           ];
    }
}