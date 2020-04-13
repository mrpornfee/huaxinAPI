<?php
/**
 * Created by PhpStorm.
 * User: 93709
 * Date: 2020/4/8
 * Time: 16:05
 */

namespace app\api\controller\zhaopin;


use app\api\model\Token;
use app\api\model\zhaopin\Company;
use think\Controller;
use think\facade\Request;
use app\api\model\zhaopin\Member;
use app\api\model\zhaopin\CompanyJob as CompanyJobModel;
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

    private function isAdmin(){
        if(self::$type==0) return 1;
        else return 0;
    }

    public function publishJob(){
        if(self::$result) return self::$result;
        $postData=initPostData();
        if($this->isAdmin()){
        $res=Member::getUid($postData['mobile']?:self::$mobile);
        }
        else {
            $res=Member::getUid(self::$mobile);
        }
        if(is_array($res))$uid=$res['uid'];
        else return $res;
        $res=Company::getComInfo($uid);
        if(is_array($res))$com_info=$res;
        else return $res;
        $data=[
            'uid'=>$uid,
            'name'=>$postData['name'],
            'job1'=>$postData['job1'],
            'job1_son'=>$postData['job1_son'],
            'job_post'=>$postData['job_post'],
            'provinceid'=>$postData['provinceid'],
            'cityid'=>$postData['cityid'],
            'three_cityid'=>$postData['three_cityid'],
            'type'=>$postData['type'],
            'number'=>$postData['number'],
            'exp'=>$postData['exp'],
            'report'=>$postData['report'],
            'sex'=>$postData['sex'],
            'edu'=>$postData['edu'],
            'marriage'=>$postData['marriage'],
            'description'=>$postData['description'],
            'sdate'=>time(),
            'cloudtype'=>$postData['cloudtype'],
            'state'=>1,
            'age'=>$postData['age'],
            'lang'=>$postData['lang'],
            'welfare'=>$postData['welfare'],
            'minsalary'=>$postData['minsalary'],
            'maxsalary'=>$postData['maxsalary'],
            'is_graduate'=>$postData['is_graduate'],
            'link_man'=>$postData['link_man']?:null,
            'link_mobile'=>$postData['link_mobile']?:null,
            'email_type'=>$postData['email_type']!==null?:1,
            'is_email'=>$postData['is_email']!==null?:1,
            'email'=>$postData['email'],
            'link_type'=>$postData['link_type']!==null?:1,
        ];
        $data=array_merge($data,$com_info);
       $res=CompanyJobModel::saveInfo($data);
       return $res;
    }

}