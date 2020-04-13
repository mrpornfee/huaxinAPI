<?php
/**
 * Created by PhpStorm.
 * User: 93709
 * Date: 2020/4/13
 * Time: 15:31
 */

namespace app\api\model\zhaopin;

use think\Exception;
use think\Model;
class CompanyJob extends Model
{
    protected  $pk='id';

    protected $name='company_job';

    public static function saveInfo($data){
        if(!isset($data['id'])){
            //添加
            try {
                model('zhaopin.CompanyJob')->allowField('true')->save($data);
                $linkData=[
                    'uid'=>$data['uid'],
                    'jobid'=>self::getLastInsID(),
                    'link_man'=>$data['link_man'],
                    'link_mobile'=>$data['link_mobile'],
                    'email_type'=>$data['email_type'],
                    'is_email'=>$data['is_email'],
                    'email'=>$data['email'],
                    'link_type'=>$data['link_type']
                ];
                CompanyJobLink::saveInfo($linkData);
            }catch (\Exception $e){
                return myJson('1003',$e->getMessage());
            }
            return myJson('1','Publish message successfully.');
        }else{
            //编辑
        }

    }
}