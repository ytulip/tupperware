<?php
include 'AipOcr.php';

//BAIDU_AIP_APP_ID = '17138239'
//BAIDU_AIP_KEY = 'PGPGu5HtsU4uRvVkOSiynlvf'
//BAIDU_AIP_SECRET = '9sGFYLfzfGnUYXWGuAbZv817uCGl0FEy'
$ocr = new AipOcr('17138239', 'PGPGu5HtsU4uRvVkOSiynlvf', '9sGFYLfzfGnUYXWGuAbZv817uCGl0FEy');


//var_dump($_FILES);
$res = $ocr->idcard(file_get_contents($_FILES["images"]["tmp_name"]), 'front');
echo json_encode($res, JSON_UNESCAPED_UNICODE);
exit;