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
    //判断是否拥有管理员权限
    private function isAdmin(){
        if(self::$type==0) return 1;
        else return 0;
    }
    //判断是否为私人手机号
    private function selfMobile($mobile){
        if($mobile==self::$mobile) return 1;
        else return 0;
    }
    //发布职位
    public function publishJob(){
        if(self::$result) return self::$result;
        $postData=initPostData();
        if($this->isAdmin()){
        $res=Member::getUid(isset($postData['mobile'])?$postData['mobile']:self::$mobile);
        }
        else {
            $res=Member::getUid(self::$mobile);
        }
        if(is_array($res))$uid=$res['uid'];
        else return $res;
        $res=Company::getComInfo($uid);
        if(is_array($res))$com_info=$res['com_info'];
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
            'description'=>$postData['description'],
            'minsalary'=>$postData['minsalary'],
            'maxsalary'=>$postData['maxsalary'],
            'sdate'=>time(),
            'edate'=>null,
            'lastupdate'=>null,
            'cert'=>null,
            'type'=>0,
            'state'=>1,
            'hy'=>isset($postData['hy'])?$postData['hy']:842,
            'number'=>isset($postData['number'])?$postData['number']:40,
            'exp'=>isset($postData['exp'])?$postData['exp']:127,
            'report'=>isset($postData['report'])?$postData['report']:54,
            'sex'=>isset($postData['sex'])?$postData['sex']:3,
            'edu'=>isset($postData['edu'])?$postData['edu']:65,
            'marriage'=>isset($postData['marriage'])?$postData['marriage']:72,
            'cloudtype'=>isset($postData['cloudtype'])?$postData['cloudtype']:null,
            'statusbody'=>isset($postData['statusbody'])?$postData['statusbody']:null,
            'x'=>isset($postData['x'])?$postData['x']:null,
            'y'=>isset($postData['y'])?$postData['y']:null,
            'age'=>isset($postData['age'])?$postData['age']:88,
            'lang'=>isset($postData['lang'])?$postData['lang']:null,
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
    //获取职位列表
    public function jobList(){
        if(self::$result) return self::$result;
        $mobile=input('mobile');
        $limit=input('limit')?:0;
        if($this->isAdmin()){
            $res=Member::getUid($mobile);
        }
        else {
            if($this->selfMobile($mobile))
             $res=Member::getUid($mobile);
            else return myJson('1004','wrong mobile number.');
        }
        if(is_array($res))$uid=$res['uid'];
        else return $res;
        $data=CompanyJobModel::getList($uid,$limit);
        return myJson('1','Its job has been listed',$data);
    }
    //删除职位
    public function delJobs(){
        if(self::$result) return self::$result;
        $deleteId=initDeleteData()['id'];
        if($this->isAdmin()){
            $res=CompanyJobModel::delJobs($deleteId);
            return $res;
        }else{
            $uid=Member::getUid(self::$mobile);
            $arr=CompanyJobModel::getList($uid);
            $idsArray=[];
            for($i=0;$i<sizeof($arr);$i++){
                array_push($idsArray,$arr[$i]['id']);
            }
            $deleteIdsArray=explode(',',$deleteId);
            $result=array_diff($deleteIdsArray,$idsArray);
            if($result) return myJson('1007','Incorrect given id');
            $res=CompanyJobModel::delJobs($deleteId);
            return $res;
        }
    }
    //查询职位
    public function searchJobInfo(){
        if(self::$result) return self::$result;
        $id=input('id');
        $mobile=CompanyJobModel::getMobileById($id);
        if(!$mobile) return myJson('1005','No information of the gotten id');
        if($this->isAdmin()){
            //无限制查询任何id的职位
            $res=CompanyJobModel::getJobDetail($id);
            return $res;
        }
        else {
            if($this->selfMobile($mobile))
               //查询id的职位
                return CompanyJobModel::getJobDetail($id);
            else return myJson('1004','wrong mobile number.');
        }

    }
    //编辑职位
    public function editJob(){
        if(self::$result) return self::$result;
        $postData=initPostData();
        $id=$postData['id'];
        if(!$this->isAdmin()) {
           $mobile= CompanyJobModel::getMobileById($id);
           if(!$this->selfMobile($mobile))
               return myJson('1004','wrong mobile number.');
        }
        if(!CompanyJobModel::isExist($id))
            return myJson('1008','the id of job is not exist');
        $data=[
            'id'=>$id,
            'name'=>$postData['name'],
            'job1'=>$postData['job1'],
            'job1_son'=>$postData['job1_son'],
            'job_post'=>$postData['job_post'],
            'provinceid'=>$postData['provinceid'],
            'cityid'=>$postData['cityid'],
            'three_cityid'=>$postData['three_cityid'],
            'description'=>$postData['description'],
            'minsalary'=>$postData['minsalary'],
            'maxsalary'=>$postData['maxsalary'],
            'lastupdate'=>time(),
            'hy'=>isset($postData['hy'])?$postData['hy']:842,
            'number'=>isset($postData['number'])?$postData['number']:40,
            'exp'=>isset($postData['exp'])?$postData['exp']:127,
            'report'=>isset($postData['report'])?$postData['report']:54,
            'sex'=>isset($postData['sex'])?$postData['sex']:3,
            'edu'=>isset($postData['edu'])?$postData['edu']:65,
            'marriage'=>isset($postData['marriage'])?$postData['marriage']:72,
            'cloudtype'=>isset($postData['cloudtype'])?$postData['cloudtype']:null,
            'statusbody'=>isset($postData['statusbody'])?$postData['statusbody']:null,
            'x'=>isset($postData['x'])?$postData['x']:null,
            'y'=>isset($postData['y'])?$postData['y']:null,
            'age'=>isset($postData['age'])?$postData['age']:88,
            'lang'=>isset($postData['lang'])?$postData['lang']:null,
            'is_graduate'=>isset($postData['is_graduate'])?$postData['is_graduate']:0,
         //   'link_man'=>isset($postData['link_man'])?$postData['link_man']:null,
          //  'link_mobile'=>isset($postData['link_mobile'])?$postData['link_mobile']:null,
          //  'email_type'=>(isset($postData['email_type'])&&$postData['email_type']!==null)?$postData['email_type']:1,
          //  'is_email'=>(isset($postData['is_email'])&&$postData['is_email']!==null)?$postData['is_email']:1,
          //  'email'=>(isset($postData['email'])&&$postData['email']!==null)?$postData['email']:null,
          //  'link_type'=>(isset($postData['link_type'])&&$postData['link_type']!==null)?$postData['link_type']:1,
        ];
        $res=CompanyJobModel::saveInfo($data);
        return $res;
    }
}