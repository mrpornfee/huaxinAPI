<?php
/**
 * Created by PhpStorm.
 * User: 93709
 * Date: 2020/4/13
 * Time: 15:49
 */

namespace app\api\model\zhaopin;
use think\Model;

//企业职位联系人
class CompanyJobLink extends Model
{
    protected $pk='id';

    protected $name='company_job_link';

    public static function saveInfo($data){
        if(!isset($data['id'])){
            //添加
               model('zhaopin.CompanyJobLink')->allowField(true)->save($data);
        }else{
            //编辑
        }
    }
}