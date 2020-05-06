<?php


namespace app\api\model\zhaopin;


use think\Model;

class ComClass extends  Model
{
    protected $pk='id';
    protected $name='comclass';

    public static function getNames($variable){
        try {
            $id = self::where(['variable' => $variable, 'display' => 1, 'keyid' => 0])->field('id')->find()['id'];
            $names=self::where(['keyid'=>$id,'display'=>1])->order(['sort'=>'asc'])->field('id,name')->select();
            return myJson('1','success get names',$names);
        }catch (\Exception $e){
            return myJson('1001',$e->getMessage());
        }
    }
}