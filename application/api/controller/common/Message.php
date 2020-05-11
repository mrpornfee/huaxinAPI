<?php


namespace app\api\controller\common;
require ROOT_PATH."vendor".DS."autoload.php";

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use think\Controller;
use think\facade\Config;
use think\facade\Session;
use think\Request;

class Message extends Controller
{
    private $session_prefix = 'MESSAGE';

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
       AlibabaCloud::accessKeyClient(Config::get('message.AccessKeyId'),decode(Config::get('message.AccessKeySecret'),Config::get('message.decodeKey')))
           ->regionId(Config::get('message.RegionId'))->asDefaultClient();
       try{
           $result=AlibabaCloud::rpc()
               ->product(Config::get('message.Product'))
               ->version(Config::get('message.Version'))
               ->action(Config::get('message.Action'))
               ->method("POST")
               ->host(Config::get('message.Host'))
               ->options([
                   "query"=>[
                       "ReginId"=>Config::get('message.RegionId'),
                       'PhoneNumbers' => $request->param("PhoneNumbers"),
                       "SignName"=>Config::get('message.SignName'),
                       "TemplateCode"=>$request->param("TemplateCode"),
                       "TemplateParam"=>json_encode([
                           "name"=>$request->param("name"),
                           "frommobile"=>$request->param("frommobile"),
                           "product"=>$request->param("product"),
                           "company"=>$request->param("company"),
                           "fromemail"=>$request->param("fromemail")
                       ])
                   ],
               ])
               ->request();
           return $result->toArray();
       } catch (ClientException $e) {
           return $e->getErrorMessage();
       } catch (ServerException $e) {
           return $e->getErrorMessage();
       }
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
        return json_encode($request->param());
        $token = $request->param('token');
        $res = $this->checkSession($token);
        if (!$res) return myJson('-2', 'You have no access to post Information');
        $visits = $this->getVisits();
        if ($visits > Config::get("message.MaxSend")) return myJson('-4', 'No allow to  post information more than 15 times  one hour');
        $res2 = $this->initMessageParam($request);
        $this->addVisits($visits + 1);
        return myJson('1','Access to visit aliyun server.',$res2);
    }


}