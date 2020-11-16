<?php


if(strtoupper($_SERVER['REQUEST_METHOD'])== 'OPTIONS'){
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: DELETE, GET, OPTIONS, PATCH, POST, PUT');
    header('Access-Control-Allow-Headers: accept, accept-encoding, authorization, content-type, dnt, origin, user-agent, x-csrftoken, x-requested-with');
    exit;
}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");

//var_dump($_REQUEST);
//exit;

$res = curl_post('http://aili.zhuyan.me/index/domain-expires-list', $_REQUEST);
echo $res;
exit;
function curl_post($url="", $data="")
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