<?php


namespace app\api\controller\common;


use think\Controller;
use think\facade\Session;
use think\Request;

class Message extends Controller
{
    private $session_prefix='MESSAGE';

    public function makeToken(Request $request){
        $ip=$request->server('REMOTE_ADDR');
        $time=$request->server('REQUEST_TIME_FLOAT');
        $client_time=$request->param('client_time');
      //  if($client_time+3<$time) return myJson('-1','time out.');
        $token=md5($ip.$time);
        session('ip',$ip,$this->session_prefix);
        session('client_time',$client_time,$this->session_prefix);
        session('token',$token,$this->session_prefix);
        return myJson('1','success.',$token);
    }


    private function checkSession($token){
        var_dump(Session::get('MESSAGE.token'));
    }

    public function sendMessage(Request $request){
        $token=$request->param('token');
        $this->checkSession($token);
    }
}