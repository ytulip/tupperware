<?php

namespace App\Util;

/**
 * 代理访问网络请求
 * Class ProxyCurl
 * @package App
 */
class ProxyCurl
{
    //curl post获取消息
    public static function curl_post($url="", $data="")
    {
        $ch = curl_init();


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

    public static function httpGet($url) {
        $curl = curl_init();


        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }


    public static function curlPost($url, $postDate, $type = false, $code = 'origin',$encode = false) {
        $ch = curl_init();




        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);


        $SSL = substr($url, 0, 8) == "https://" ? true : false;
        if ($SSL) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        }

        //设置请求为post类型
        curl_setopt($ch, CURLOPT_POST, TRUE);
        //添加post类型
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postDate);
        //执行请求，获得回复
        $r = curl_exec($ch);
        if($encode){
            $check = mb_detect_encoding($r, array('ASCII','GB2312','GBK'));
            $r = iconv($check, 'UTF-8', $r);
        }
        curl_close($ch);
        var_dump($r);
        switch ($code) {
            case 'json' :
                if ($type) {
                    return json_decode($r, true);
                }
                return json_decode($r);
                break;
            case 'origin' :
                return $r;
                break;
        }
        return null;
    }
}
