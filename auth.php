<?php
session_start();
function random()
{
    $str = '';
    for($i=0;$i<5;$i++)
    {
        $str .= rand('a', 'z');
    }
    return $str;
}
$appid = 'wx3602f3ba4c085c82';
$redirect = 'http://wx.alusachen.com/auth_return.php';
$redirect = urlencode($redirect);
$state = random();
$_SESSION['wx_state'] = $state;
$scope = 'snsapi_base';
$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid
    .'&redirect_uri='.$redirect
    .'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect';

header('Location: '.$url);
