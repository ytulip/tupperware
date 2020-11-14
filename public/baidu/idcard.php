<?php
include 'AipOcr.php';
//echo '{"words_result":{"姓名":{"words":"程浩","location":{"top":265,"left":244,"width":112,"height":43}},"民族":{"words":"汉","location":{"top":352,"left":442,"width":25,"height":29}},"住址":{"words":"广东省深圳市福田区深南中路1002号新闻大厦1号楼31层","location":{"top":504,"left":237,"width":410,"height":139}},"公民身份号码":{"words":"450103197909212030","location":{"top":705,"left":399,"width":530,"height":37}},"出生":{"words":"19790921","location":{"top":426,"left":244,"width":315,"height":35}},"性别":{"words":"男","location":{"top":354,"left":248,"width":26,"height":30}}},"log_id":1.3275017986378e+18,"words_result_num":6,"idcard_number_type":1,"image_status":"normal"}';
//exit;
//BAIDU_AIP_APP_ID = '17138239'
//BAIDU_AIP_KEY = 'PGPGu5HtsU4uRvVkOSiynlvf'
//BAIDU_AIP_SECRET = '9sGFYLfzfGnUYXWGuAbZv817uCGl0FEy'
$ocr = new AipOcr('17138239', 'PGPGu5HtsU4uRvVkOSiynlvf', '9sGFYLfzfGnUYXWGuAbZv817uCGl0FEy');

$data = $_POST['data'];
$type = $_POST['type'];
//var_dump($_FILES);
$res = $ocr->idcard($data, $type);
echo json_encode($res, JSON_UNESCAPED_UNICODE);