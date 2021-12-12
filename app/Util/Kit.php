<?php

namespace  App\Util;

use Illuminate\Support\Facades\Log;

class Kit
{
    public static function phoneHide($phone)
    {
        return substr_replace($phone,'****',3,4);
    }

    public static function phoneIdCard($idCard)
    {
        return substr_replace($idCard,'**************',2,14);
    }

    public static function columnMonthFilter($query,$month,$column = 'created_at')
    {
        $query->whereRaw('DATE_FORMAT('.$column.',"%Y-%m") = "' . $month . '"');
    }

    public static function issetThenReturn($obj,$attr)
    {
        return isset($obj->$attr)?$obj->$attr:'';
    }

    public static function priceFormat($price)
    {
        return number_format($price,2);
    }

    public static function dateFormat($dateStr)
    {
        return date('Y.m.d H.i',strtotime($dateStr));
    }

    public static function dateFormat2($dateStr)
    {
        return date('m月d日 H:i',strtotime($dateStr));
    }

    /**
     * @desc 相等查询
     * @param $query
     * @param $arr
     */
    static public function equalQuery($query,$arr){
        $arr = array_filter($arr,function($val){

            if( $val == "全部") return false;

            if($val === '0' || $val === 0){
                return true;
            }
            return $val?true:false;
        });
        if($arr){
            $query->where($arr);
        }
    }

    static public function likeQuery($query,$arr){
        $arr = array_filter($arr,function($val){

            if( $val == "全部") return false;

            if($val === '0' || $val === 0){
                return true;
            }
            return $val?true:false;
        });
        if($arr){
            $query->where($arr);
        }
    }

    public static function compareBelowZeroQuery($query,$arr)
    {
        $arr = array_filter($arr,function($val){
            if($val === '0' || $val === 0){
                return true;
            }
            return $val?true:false;
        });

        foreach ($arr as $key=>$item)
        {
            if( $item )
            {
                $query->where($key,'>',0);
            }else
            {
                $query->where($key,'=',0);
            }
        }
    }

    public static function isWechat()
    {
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ){  //微信
            return true;
        }

        return false;
    }



    /**
     *
     * 拼接签名字符串
     * @param array $urlObj
     *
     * @return 返回已经拼接好的字符串
     */
    public static function ToUrlParams($param)
    {
        $buff = "";
        ksort($param);
        foreach ($param as $k => $v) {
            $k = urlencode($k);
            $v = urlencode($v);
            $buff .= $k . "=" . $v . "&";
        }
        if (strlen($buff) > 0) {
            return substr($buff, 0, strlen($buff) - 1);
        }
        return $buff;
    }


    public static  function MakeSign($values)
    {
        //签名步骤一：按字典序排序参数
        ksort($values);
        $string = self::ToUrlParams($values);
        $key = '8a58e481cad44297bfde0ddb92587856';
//    //签名步骤二：在string后加入KEY
        $string = $string . "&key=". '8a58e481cad44297bfde0ddb92587856';

//        var_dump($string);
//        //签名步骤三：MD5加密或者HMAC-SHA256
        $string = hash_hmac("sha256",$string, '8a58e481cad44297bfde0ddb92587856');
//        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

//curl post获取消息
    public static function curlGet($url, $getDate, $type = false, $code = 'json',$encode = false) {
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
        var_dump($url);
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
        var_dump($r);
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
//        var_dump($res);
        return $res;
    }


    public static function sendInsureSms($mobile, $brand)
    {
        self::curl_post('https://api.mysubmail.com/message/send', [
            "appid"=>"62753",
            "to"=>$mobile,
            "content"=>"【APAPPF】感谢您选用APAPPF车衣，您的质保单号为 ".$brand." ，更多资讯，请微信搜索 APAPPF 小程序！退订回N",
            "signature"=>"134b1fa3ce6d16b2ee27dec867a3f079"
        ]);
    }

}
