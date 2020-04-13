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
            'type'=>isset($postData['type'])?$postData['type']:0,
            'number'=>isset($postData['number'])?$postData['number']:40,
            'exp'=>isset($postData['exp'])?$postData['exp']:127,
            'report'=>isset($postData['report'])?$postData['report']:54,
            'sex'=>isset($postData['sex'])?$postData['sex']:3,
            'edu'=>isset($postData['edu'])?$postData['edu']:65,
            'marriage'=>isset($postData['marriage'])?$postData['marriage']:72,
            'description'=>isset($postData['description'])?$postData['description']:null,
            'sdate'=>time(),
            'cloudtype'=>isset($postData['cloudtype'])?$postData['cloudtype']:null,
            'state'=>1,
            'age'=>isset($postData['age'])?$postData['age']:88,
            'lang'=>isset($postData['lang'])?$postData['lang']:null,
            'welfare'=>isset($postData['welfare'])?$postData['welfare']:null,
            'minsalary'=>$postData['minsalary'],
            'maxsalary'=>$postData['maxsalary'],
            'is_graduate'=>isset($postData['is_graduate'])?$postData['is_graduate']:0,
            'link_man'=>isset($postData['link_man'])?$postData['link_man']:null,
            'link_mobile'=>isset($postData['link_mobile'])?$postData['link_mobile']:null,
            'email_type'=>(isset($postData['email_type'])&&$postData['email_type']!==null)?$postData['email_type']:1,
            'is_email'=>(isset($postData['is_email'])&&$postData['is_email']!==null)?$postData['is_email']:1,
            'email'=>(isset($postData['email'])&&$postData['email']!==null)?$postData['email']:null,
            'link_type'=>(isset($postData['link_type'])&&$postData['link_type']!==null)?$postData['link_type']:1,
        ];
        $data=array_merge($data,$com_info);
       $res=CompanyJobModel::saveInfo($data);
       return $res;
    }

}