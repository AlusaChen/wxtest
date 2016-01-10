<?php
session_start();
$code = $_GET['code'];
$state = $_GET['state'];
if($state != $_SESSION['wx_state'])
{
    echo 'error state';
    return;
}
if(!$code)
{
    echo 'no code';
    return;
}

$appid = 'wx3602f3ba4c085c82';
$secret = 'e72b58b099303b5f99dd4f25975a6444';
$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";

require_once __DIR__.'/Curl/autoload.php';
$curl = new \Curl\Curl();
$ret = $curl->get($url);
echo ($ret);
file_put_contents('/tmp/wx_data.log', $ret, FILE_APPEND);