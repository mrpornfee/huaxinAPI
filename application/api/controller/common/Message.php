<?php


namespace app\api\controller\common;


use think\Controller;
use think\facade\Config;
use think\facade\Session;
use think\Request;

class Message extends Controller
{
    private $session_prefix = 'MESSAGE';

    private $Endpoint = 'dysmsapi.aliyuncs.com';

    private $SignatureNonce;

    private $Timestamp;

    //制作Token
    public function makeToken(Request $request)
    {
        $ip = $request->server('REMOTE_ADDR');
        $time = $request->server('REQUEST_TIME_FLOAT');
        $client_time = $request->param('client_time');
        if ($client_time + 3 < $time) return myJson('-1', 'time out.');
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
        $Signature=$this->makeSignature($request);
        $message_config['AccessKeyId'] = Config::get('message.AccessKeyId');
        $message_config['SignatureMethod'] = Config::get('message.SignatureMethod');
        $message_config['SignatureNonce'] =  $this->SignatureNonce;
        $message_config['SignatureVersion'] = Config::get('message.SignatureVersion');
        $message_config['SignName'] = Config::get('message.SignName');
        $message_config['Action'] = Config::get('message.Action');
        $message_config['Timestamp']=$this->Timestamp;
        $message_config['Version'] = Config::get('message.Version');
        $message_config['RegionId'] = Config::get('message.RegionId');
        $message_config['Format'] = Config::get('message.Format');
        $message_config['PhoneNumbers'] = $request->param('PhoneNumbers');
        $message_config['TemplateCode'] = $request->param('TemplateCode');
        $arr = ['name' => $request->param('name'),'frommobile' => $request->param('frommobile'), 'product' => $request->param('product'),'company' => $request->param('company'),'fromemail' => $request->param('fromemail')];
        $message_config['TemplateParam'] = json_encode($arr);
        ksort($message_config);
        $query="";
        foreach ($message_config as $k => $v){
            $query.="$k=".urlencode($v)."&";
        }
        return $query."Signature=$Signature";
    }

    private function getVisits()
    {
        $visits = Session::get('MESSAGE.visits') ?: 0;
        return $visits;
    }

    private function addVisits($n)
    {
        Session::set('MESSAGE.visits', $n);
    }

    //发送短信
    public function sendMessage(Request $request)
    {
        $token = $request->param('token');
        $res = $this->checkSession($token);
        if (!$res) return myJson('-2', 'You have no access to post Information');
        $visits = $this->getVisits();
        if ($visits > 15) return myJson('-4', 'No allow to  post information more than 15 times  one hour');
        $res2 = $this->initMessageParam($request);
        $url = $this->Endpoint . '/?' .$res2;
        $res = curl_request($url);
        $this->addVisits($visits + 1);
        return $res;
    }

    //请求签名
    private function makeSignature( $request)
    {
        $arr['SignatureMethod'] = Config::get('message.SignatureMethod');
        $arr['SignatureNonce'] = Config::get('message.SignatureNonce');
        $this->SignatureNonce=$arr['SignatureNonce'];
        $arr['AccessKeyId'] = Config::get('message.AccessKeyId');
        $arr['SignatureVersion'] = Config::get('message.SignatureVersion');
        $arr['Timestamp']=Config::get('message.Timestamp');
        $this->Timestamp=$arr['Timestamp'];
        $arr['Format'] = Config::get('message.Format');
        $arr['Action'] = Config::get('message.Action');
        $arr['Version'] = Config::get('message.Version');
        $arr['RegionId'] = Config::get('message.RegionId');
        $arr['PhoneNumbers'] = $request->param('PhoneNumbers');
        $arr['SignName'] = Config::get('SignName');
        $arr['TemplateParam'] = json_encode(['name' => $request->param('name'),'frommobile' => $request->param('frommobile'), 'product' => $request->param('product'), 'company' => $request->param('company'), 'fromemail' => $request->param('fromemail')]);
        $arr['TemplateCode'] = $request->param('TemplateCode');
        ksort($arr);
        $arr_query='';
        foreach ($arr as $k =>$v){
            $arr_query.="$k=".urlencode($v)."&";
        }
        $arr_query=substr($arr_query,0,-1);
        $arr_query="GET&".sign(urlencode("/"))."&".sign(urlencode($arr_query));
        $sign=base64_encode(hash_hmac('sha1',$arr_query,Config::get('message.AccessKeySecret').'&',true));
        return sign(urlencode($sign));
    }

}