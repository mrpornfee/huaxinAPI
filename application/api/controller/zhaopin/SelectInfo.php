<?php
/**
 * Created by PhpStorm.
 * User: 93709
 * Date: 2020/4/26
 * Time: 13:50
 */

namespace app\api\controller\zhaopin;
use app\api\model\zhaopin\JobClass;
use app\api\model\zhaopin\CityClass;
class SelectInfo
{
    //查询职位分类
    public function selectJob(){
        $jobClass=input('job_class');
        if($jobClass==="1"){
            $id=input('id');
            if(!$id||$id<=0) return myJson('1005','Invalid parameter id.');
            $res=JobClass::selectChildJobs($id);
            return $res;
        }
        if($jobClass==="0"){
            $res=JobClass::selectJob_0_1();
            return $res;
        }
        return myJson('1001','Invalid parameter jobClass.');
    }

    //查询地域
    public function selectDes(){
        $desClass=input('des_class');
        if($desClass==="1"){
            $id=input('id');
            if(!$id||$id<=0) return myJson('1005','Invalid parameter id.');
            $res=CityClass::getNextDes($id);
            return $res;
        }
        if($desClass==="0"){
            $res=CityClass::getFirstDes();
            return $res;
        }
        return myJson('1001','Invalid parameter desClass.');
    }
}