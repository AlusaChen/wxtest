<?php
//get access_token
require_once __DIR__.'/wx.php';
/*
$token = Wx::get_access_token();
echo '<pre>';
print_r($token);
echo '</pre>';
*/

/*
$img_path = '/Users/alusa/Downloads/2.jpg';
$info = Wx::upload_image($img_path);

echo '<pre>';
print_r($info);
echo '</pre>';
*/
$info = Wx::get_openids();
echo '<pre>';
print_r($info);
echo '</pre>';

$next_id = $info->next_openid;
$info = Wx::get_openids($next_id);
echo '<pre>';
print_r($info);
echo '</pre>';