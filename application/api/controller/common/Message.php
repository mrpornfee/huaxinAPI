<?php


namespace app\api\controller\common;


use think\Controller;
use think\facade\Config;
use think\facade\Session;
use think\Request;

class Message extends Controller
{
    private $session_prefix = 'MESSAGE';

    private $Endpoint='dysmsapi.aliyuncs.com';
    //制作Token
    public function makeToken(Request $request)
    {
        $ip = $request->server('REMOTE_ADDR');
        $time = $request->server('REQUEST_TIME_FLOAT');
        $client_time = $request->param('client_time');
        //  if($client_time+3<$time) return myJson('-1','time out.');
        $token = md5($ip . $time);
        session('ip', $ip, $this->session_prefix);
        session('client_time', $client_time, $this->session_prefix);
        session('token', $token, $this->session_prefix);
        return myJson('1', 'success.', $token);
    }

    //token值验证
    private function checkSession($token)
    {
        $cache_token = Session::get('MESSAGE.token');
        if ($cache_token == $token) return true;
        else return false;
    }
    //初始化短信参数
    private function initMessageParam($request)
    {
        $message_config = Config::get('message.');
        if (!$request->param('TemplateCode')) return -1;
        $message_config['TemplateCode'] = $request->param('TemplateCode');
        $arr = ['name' => $request->param('name'),
            'frommobile' => $request->param('frommobile'),
            'product' => $request->param('product'),
            'company' => $request->param('company'),
            'fromemail' => $request->param('fromemail')
        ];
        $message_config['TemplateParam'] =json_encode($arr);
        return http_build_query($message_config);
    }

    private function getVisits(){
        $visits = Session::get('MESSAGE.visits')?:0;
        return $visits;
    }

    private function addVisits($n){
        Session::set('MESSAGE.visits',$n);
    }

    //发送短信
    public function sendMessage(Request $request)
    {
        $token = $request->param('token');
        $res = $this->checkSession($token);
        if (!$res) return myJson('-2', 'You have no access to post Information');
        $visits=$this->getVisits();
        if($visits>15) return myJson('-4','No allow to  post information more than 15 times  one hour');
        $res2 = $this->initMessageParam($request);
        if ($res2 == -1) return myJson('-3', 'Wrong! TemplateCode is neccessary.');
        $url=$this->Endpoint.'/?'.$res2;
        $res=curl_request($url);
        $this->addVisits($visits+1);
        return myJson('1',"Visit $this->Endpoint successfully.",$res);
    }
}