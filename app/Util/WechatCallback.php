<?php
namespace App\Util;

class WechatCallback{
    //默认的token为wechat
    public $_appid = null;
    private $_appsercret = null;
    private $_token = 'wechat';
    private $_reponse_obj = null;
    private $_token_path = null;
    private $_ticket_path = null;
    private $_menu_path = null;
    private $_config = null;
    public $delegate = true;
    private $_token_storage = '';

    /**
     * 配置数组
     * @param array $config
     * @param WechatResponse $response 控制反转的对象
     */
    public function __construct(){
//        $response = new WechatResponse();


        $this->_token = '123';
//        $this->_reponse_obj = $response;
        $this->_menu_path = '';
        $this->_token_path = 'access_token';
        $this->_ticket_path = 'jsapi_ticket';
        $this->_appid = 'wx348526119ca2e124';
        $this->_appsercret = '1479d8b9396900d46fd53f9b2f4a7dd3';
        $this->_token_storage = WechatTokenStroage::getInstance();


    }


    //生成32位随机数
    public function rand_num(){
        $str="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $key = "";
        for($i=0;$i<32;$i++)
        {
            $key .= $str{mt_rand(0,60)};    //生成php随机数
        }
        return $key;
    }


    /**
     * 返回验证串
     * @return mixed
     */
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            ob_clean();
            echo $echoStr;
            exit;
        }
    }


    public function response($data){
        $this->_reponse_obj->init($data);
        return $this->_reponse_obj->response();
    }

    /**
     *
     */

    /**
     * 设置菜单
     */
    public function setMenu(){

        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $this->getAccessToken();
        $data = '{
          "button":[
            {
                "type": "view", 
                "name": "去玩", 
                "url": "http://yq.zhuyan.me/activity", 
                "sub_button": [ ]
            }
        ]
        }';
        return self::curl_post($url,$data);
    }

    public function getAppId(){
        return $this->_appid;
    }

    public function getAppSecret()
    {
        return $this->_appsercret;
    }


    /**
     * 在当前页面获得用户的openid
     */
    public function getOpenidInThisUrlDealWithError($errorCallback){
        //通过code获得openid
        if (!isset($_GET['code'])){
            //触发微信返回code码
            $baseUrl = urlencode($_SERVER['REQUEST_SCHEME'] . '://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
            $url = $this->__CreateOauthUrlForCode($baseUrl);
            Header("Location: $url");
            exit();
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $info = $this->getAuth2Info($code);
            if(isset($info['errcode'])){
                if($info['errcode'] == 40029)
                    $errorCallback();
            }
            $openid = $info['openid'];
            return $openid;
        }
    }


    /**
     * 获得auth信息
     * @param string $code
     * @return mixed
     */
    public function getAuth2Info($code){
        //require_once app_path().'/kits/Curl.class.php';
        $response = self::curlGet('https://api.weixin.qq.com/sns/oauth2/access_token',array('appid'=>$this->_appid,'secret'=>$this->_appsercret,'code'=>$code,'grant_type'=>'authorization_code'),true);
        return $response;
    }

    /**
     * @param $openid
     * @return mixed
     */
    public function snsUserInfo($access_token, $openid){
        $response = $this->curlGet('https://api.weixin.qq.com/sns/userinfo',array('access_token'=>$access_token,'openid'=>$openid,'lang'=>'zh_CN'),true);
        Logger::info('用户信息' . json_encode($response), 'wx_code');
        return $response;
    }

    public function customSend($openid, $content)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $this->getAccessToken();
        $data = '{
            "touser":"'. $openid .'",
            "msgtype":"text",
            "text":
            {
                 "content": "'. $content .'"
            }
        }';
        return self::curl_post($url,$data);
    }


    public function templateSend($tempId,$openid,$messageData,$redirectTo)
    {
//        https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=ACCESS_TOKEN
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $this->getAccessToken();

        if($redirectTo)
        {
            $data = ' {
           "touser":"'.$openid.'",
           "template_id":"'.$tempId.'", 
           "url":"'.$redirectTo.'", 
           "data":' . json_encode($messageData) . '}';
        } else
        {
            $data = ' {
           "touser":"'.$openid.'",
           "template_id":"'.$tempId.'", 
           "data":' . json_encode($messageData) . '}';
        }
        return self::curl_post($url,$data);
    }


    /**
     * 在当前页面获得用户的openid
     */
    public function getOpenidInThisUrl(){
        //通过code获得openid
        if (!isset($_GET['code'])){
            //触发微信返回code码
            $baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
            $url = $this->__CreateOauthUrlForCode($baseUrl);
            Header("Location: $url");
            exit();
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $info = $this->getAuth2Info($code);
            $openid = $info['openid'];
            return $openid;
        }
    }


    /**
     * 在当前页面获得用户的openid
     */
    public function getInfoInThisUrl(){
        //通过code获得openid
        if (!isset($_GET['code'])){
            //触发微信返回code码
            $baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
            $url = $this->__CreateOauthUrlForCode($baseUrl);
            Header("Location: $url");
            exit();
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $info = $this->getAuth2Info($code);
            return $info;
        }
    }

    /**
     * 获得支付参数
     * @param $config
     */
    public function getPayParams($config){
        require_once 'lib/WxPay.JsApiPay.php';

        //①、获取用户openid
        $tools = new \JsApiPay();
        $openId = $tools->GetOpenid();

        //②、统一下单
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("test");
        $input->SetAttach("test");
        $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = \WxPayApi::unifiedOrder($input);

        $jsApiParameters = $tools->GetJsApiParameters($order);

        $editAddress = $tools->GetEditAddressParameters();

        return [
            'js_api_parameters'=>$jsApiParameters,
            'edit_address'=>$editAddress
        ];
    }

    /**
     * 获得微信token
     */
    public function getAccessToken($force = false){
        self::check();
        $jssdk = new JSSDK($this->_appid,$this->_appsercret,$this->_token_path,$this->_ticket_path);
        if( $force )
        {
            return $jssdk->getAccessToken($force);
        } else
        {
            return $jssdk->getAccessToken();
        }
    }


    public function getJsApiTicket()
    {
        self::check();
        $jssdk = new JSSDK($this->_appid,$this->_appsercret,$this->_token_path,$this->_ticket_path);
        return $jssdk->getJsApiTicket();
    }

    /**
     * 检测文件是否存在,如果不存在则创建
     */
    private function check(){
        $accessTokenFile = $this->_token_path;
        $jsapiTicketFile = $this->_ticket_path;
        $accessTokenData =json_encode(array(
            'access_token'=>'',
            'expire_time'=>0,
        ));
        $jsapiTicketData = json_encode((object)(array(
            'jsapi_ticket'=>'',
            'expire_time'=>0,
        )));

        if( !$this->_token_storage->get($accessTokenFile)){
            //创建文件
//            $fp = fopen($accessTokenFile, "w");
//            fwrite($fp, $accessTokenData);
//            fclose($fp);
//            chmod($accessTokenFile,0660);
            $this->_token_storage->set($accessTokenFile,$accessTokenData);
        }
        if(!$this->_token_storage->get($jsapiTicketFile)){
//            $fp = fopen($jsapiTicketFile,"w");
//            fwrite($fp, $jsapiTicketData);
//            fclose($fp);
//            chmod($jsapiTicketFile,0660);
            $this->_token_storage->set($jsapiTicketFile,$jsapiTicketData);
        }
    }

    public function testRequiredOnce(){
        require_once 'lib/WxPay.JsApiPay.php';
        echo 1;
        exit;
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = $this->_token;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $openid
     * @return mixed
     */
    public function userInfo($openid){
        $response = $this->curlGet('https://api.weixin.qq.com/cgi-bin/user/info',array('access_token'=>$this->getAccessToken(),'openid'=>$openid,'lang'=>'zh_CN'));
        if(isset($response->subscribe)){
            return $response;
        }else{
            return false;
        }
    }



    //微信支付接口
    public function curl_post_ssl($url, $vars, $second=30,$aHeader=array())
    {
        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);

        //默认格式为PEM，可以注释
//        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT,dirname(getcwd()).'/storage/certificate/apiclient_cert.pem');
        curl_setopt($ch,CURLOPT_SSLKEY,dirname(getcwd()).'/storage/certificate/apiclient_key.pem');
        curl_setopt($ch,CURLOPT_CAINFO,dirname(getcwd()).'/storage/certificate/rootca.pem');
//        curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');

        if( count($aHeader) >= 1 ){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }

        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
        $data = curl_exec($ch);

        Log::info($data);
        if($data){
            curl_close($ch);
            return $data;
        }
        else {
            $error = curl_errno($ch);
            //echo "call faild, errorCode:".$error."\n";
            Log::info($data);
            curl_close($ch);
            return false;
        }
    }

    static public function testVisit(){
        return 123;
    }


    //curl post获取消息
    public function curl_post($url="", $data="")
    {
        $ch = curl_init();


        //设置代理
        if( Env::get('CURL_PROXY')){
            curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
            curl_setopt($ch,CURLOPT_PROXY,Env::get('PROXY_SERVER'));
            curl_setopt($ch,CURLOPT_PROXYPORT,Env::get('PROXY_PORT'));
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, Env::get('PROXY_USERPWD') ); //http代理认证帐号，username:password的格式
        }


        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);//设定为不验证证书和host
        curl_setopt ( $ch, CURLOPT_URL, $url);
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        $res = curl_exec($ch);
        return $res;
    }

    /*
     * curl发送post请求接收返回的数据但不输出
     * param url
     * param array('key1'=>'value1','key2'=>'value2',...)
     * @param bool $encode 编码装换成utf8
     */
    public function curlGet($url, $getDate, $type = false, $code = 'json',$encode = false) {
        $ch = curl_init();
        if (!empty($getDate) && is_array($getDate)) {
            $i = 0;
            foreach ($getDate as $k => $v) {
                ++$i;
                if ($i == 1) {
                    $url = ($url . '?' . $k . '=' . $v);
                } else {
                    $url .= ('&' . $k . '=' . $v);
                }
            }
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT,10); //超时

        $SSL = substr($url, 0, 8) == "https://" ? true : false;
        if ($SSL) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        }
        //执行请求，获得回复
        $r = curl_exec($ch);
        if($encode){
            $check = mb_detect_encoding($r, array('ASCII','GB2312','GBK', 'UTF-8'));
            $r = iconv($check, 'UTF-8', $r);
        }
        curl_close($ch);
        switch ($code) {
            case 'json' :
                if($type)
                {
                    return json_decode($r,true);
                }
                return json_decode($r);
                break;
            case 'origin' :
                return $r;
                break;
        }
        return null;
    }


    /**
     *
     * 构造获取code的url连接
     * @param string $redirectUrl 微信服务器回跳的url，需要url编码
     *
     * @return 返回构造好的url
     */
    private function __CreateOauthUrlForCode($redirectUrl)
    {
        $urlObj["appid"] = $this->_appid;
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = "snsapi_base";
        $urlObj["state"] = "STATE"."#wechat_redirect";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
    }


    /**
     *
     * 拼接签名字符串
     * @param array $urlObj
     *
     * @return 返回已经拼接好的字符串
     */
    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign"){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }


    public function getSignPackage2() {
        $jsapiTicket = $this->getJsApiTicket();
        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $url = str_replace(':8000', '', $url);
        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId" => $this->_appid,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature
        );
        return $signPackage;
    }


    public function getSignPackage3($url) {
        $jsapiTicket = $this->getJsApiTicket();
        // 注意 URL 一定要动态获取，不能 hardcode.
        $nonceStr = $this->createNonceStr();
        $timestamp = time();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId" => $this->_appid,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature
        );
        return $signPackage;
    }


    //计算签名
    public function getSign($data,$the_key){
        ksort($data);
        $stringA = '';
        foreach($data as $key => $value){
            if(empty($value)){
                continue;
            }else{
                $stringA .= $key.'='.$value.'&';
            }
        }
        $stringSignTemp = $stringA."key=".$the_key;
        $sign = strtoupper(MD5($stringSignTemp));
        return $sign;
    }


    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}
