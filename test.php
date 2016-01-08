<?php
//get access_token
require_once __DIR__.'/wx.php';

function debug($content)
{
    echo '<pre>';
    print_r($content);
    echo '</pre>';
}

/*
$token = Wx::get_access_token();
echo '<pre>';
print_r($token);
echo '</pre>';
*/

 //上传图片
/*
$img_path = '/Users/alusa/Downloads/2.jpg';
$info = Wx::upload_image($img_path);
echo '<pre>';
print_r($info);
echo '</pre>';
*/

/*
$info = Wx::get_openids();
echo '<pre>';
print_r($info);
echo '</pre>';

$next_id = $info->next_openid;
$info = Wx::get_openids($next_id);
echo '<pre>';
print_r($info);
echo '</pre>';


$ret = Wx::send_message();
echo '<pre>';
print_r($ret);
echo '</pre>';
*/

//upload media
/*
$media_info = [
    'path' => '/Users/alusa/Downloads/1.jpg',
    'type' => 'image/jpg'
];
$ret = Wx::upload_media($media_info);
debug($ret);
*/
/*
$mid = 'N6zwTj8N5YoMUQPw6C31uMGzSgi23PKermSAEqd4scV8H7_iIMp5AX0EeZ2RinRV';
$ret = Wx::get_media($mid);
debug($ret);

$ret = Wx::add_material();
debug($ret);
*/
/*
$ret = Wx::get_material_count();
debug($ret);
*/