<?php
//get access_token
require_once __DIR__.'/wx.php';
$token = Wx::get_access_token();
echo '<pre>';
print_r($token);
echo '</pre>';
/*
*/

