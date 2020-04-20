<?php
/**
 * Created by PhpStorm.
 * User: 93709
 * Date: 2020/4/13
 * Time: 15:31
 */

namespace app\api\model\zhaopin;
use think\Db;
use think\Exception;
use think\Model;
class CompanyJob extends Model
{
    protected  $pk='id';

    protected $name='company_job';

    public static function saveInfo($data){
        if(!isset($data['id'])){
            //添加
            Db::startTrans();
            try {
                model('zhaopin.CompanyJob')->allowField(true)->create($data);
                $jobid=model('zhaopin.CompanyJob')->getLastInsID();
                if($jobid==0) throw new Exception("jobid can not be 0");
                $linkData=[
                    'uid'=>$data['uid'],
                    'jobid'=>$jobid,
                    'link_man'=>$data['link_man'],
                    'link_mobile'=>$data['link_mobile'],
                    'email_type'=>$data['email_type'],
                    'is_email'=>$data['is_email'],
                    'email'=>$data['email'],
                    'link_type'=>$data['link_type']
                ];
                CompanyJobLink::saveInfo($linkData);
                Db::commit();
            }catch (\Exception $e){
                Db::rollback();
                return myJson('1003',$e->getMessage());
            }
            return myJson('1','Publish message successfully.');
        }else{
            //编辑
            try{
               model('zhaopin.CompanyJob')->allowField(true)->update($data);
            }catch (\Exception $e){
                return myJson('1003',$e->getMessage());
            }
            return myJson('1','Renew message successfully.');
        }

    }

    public static function getList($uid,$limit=0){
        if($limit==0)
            $data=self::where(['uid'=>$uid])->field('id,name')->select();
        else $data=self::where(['uid'=>$uid])->field('id,name')->limit($limit)->select();
           return $data;
    }

    //根据id获取手机号
    public static function getMobileById($id){
        $uid=self::where(['id'=>$id])->field('uid')->find()['uid']?:0;
        $mobile=Member::where(['uid'=>$uid])->field('moblie')->find()['moblie'];
        if($mobile) return $mobile;
        else return 0;
    }
    //根据id获取职位详情
    public static function getJobDetail($id){
        $detail=self::where(['id'=>$id])->field('id,uid,name,com_name,hy,job1,job1_son,job_post,provinceid,cityid,three_cityid,description,minsalary,maxsalary,hy,number,exp,sex,report,marriage,edu,age,lang')->find();
        return myJson('1','Here is the information of the id:',$detail);
    }
    //根据id删除职位
    public static function delJobs($id){
        $ids=','.$id.',';
        try {
            $a=self::whereIn('id', $ids)->delete();
        }catch (\Exception $e){
            return myJson('1006',$e);
          }
        if($a)return myJson('1','Successfully deleted');
        else return myJson('1008','Can not find Information of these ids');
    }
    //判断id是否存在表中
    public static function isExist($id){
        if(self::where('id',$id)->find()) return 1;
        else return 0;
    }
    //判断id name 是否匹配
    public static function validateIdName(array $data){
       if(self::isExist($data['id'])) {
           $name = self::where(['id' => $data['id']])->field('name')->find()['name'];
           if ($name == $data['name']) return true;
       }
        else return myJson('1002','Incorrect id or name post');
    }
    //置顶职位
    public static function toTop($id,$time){
        $res=self::where('id',$id)->update(['xsdate'=>$time]);
        if($res) return true;
        else return myJson('1003','Top operation failed.');
    }
    //取消置顶
    public static function topOff($id){
        $res=self::where('id',$id)->update(['xsdate'=>0]);
        if($res) return true;
        else return myJson('1003','TopOff operation failed.');
    }
}