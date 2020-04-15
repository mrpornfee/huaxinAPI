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
        }

    }

    public static function getList($uid,$limit){
        if($limit==0)
            $data=self::where(['uid'=>$uid])->field('id,name')->select();
        else $data=self::where(['uid'=>$uid])->field('id,name')->limit($limit)->select();
           return myJson('1','Its job has been listed',$data);
    }
}