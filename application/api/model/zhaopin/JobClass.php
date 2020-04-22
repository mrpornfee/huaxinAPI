<?php
/**
 * Created by PhpStorm.
 * User: 93709
 * Date: 2020/4/22
 * Time: 14:41
 */

namespace app\api\model\zhaopin;

use think\Model;
class JobClass extends  Model
{

    protected $pk='id';

    protected $name='job_class';

    public static function selectJob_0_1(){
            try{
                $res=self::where(['keyid'=>0])->field('id,name')->select()->toArray();
                foreach ($res as $k=>$v){
                    $res[$k]['child']=self::where('keyid',$v['id'])->field('id,name')->select();
                }
            }catch (\Exception $e){
                return myJson('1003',$e->getMessage());
            }
            return myJson('1','Success',$res);
    }

    public static function selectChildJobs($id){
        try {
            $res = self::where(['keyid' => $id])->field('id,name')->order(['sort' => 'asc'])->select();
        }catch (\Exception $e){
            return myJson('1003',$e->getMessage());
        }
        return myJson('1','Success',$res);
    }
}