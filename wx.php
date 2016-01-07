<?php

require_once __DIR__.'/Curl/autoload.php';

use Curl\Curl;

class Wx
{
    protected static $access_token_log = __DIR__.'/data/access.log';
    protected static $msg_file = __DIR__.'/data/msg.log';
    protected static $file_file = __DIR__.'/data/file.log';

    protected static $config = [
        'appID'     => 'wx3602f3ba4c085c82',
        'appsecret' => 'e72b58b099303b5f99dd4f25975a6444',
        'Token'     => '076e55fd0ca978497b04c992d4f5d957'
    ];

    public static $curl = null;

    public static function curl()
    {
        if(!self::$curl) self::$curl = new Curl();
        return self::$curl;
    }

    public static function get_access_token()
    {
        if(file_exists(self::$access_token_log))
        {
            $contents = file_get_contents(self::$access_token_log);
            if($contents)
            {
                $info = json_decode($contents, true);
                if($info['last_time'] && (int)$info['last_time'] > (time()- 7000) && $info['access_token'])
                {
                    return $info['access_token'];
                }
            }
        }

        $info = self::refresh_access_token();
        if($info)
        {
            return $info['access_token'];
        }
        return false;
    }

    protected static function refresh_access_token()
    {
        $appid = self::$config['appID'];
        $secret = self::$config['appsecret'];
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret;
        $curl = self::curl();
        $ret = $curl->get($url);
        $info = json_decode(json_encode($ret), true);
        if($info['errcode'])
        {
            echo $info['errmsg'];
            return false;
        }
        else
        {
            $info['last_time'] = time();
            file_put_contents(self::$access_token_log, json_encode($info));
            return $info;
        }
    }

    public static function upload_image($img_path)
    {
        $access_token = self::get_access_token();
        if(!$access_token) throw new Exception('error access token');
        $url = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.$access_token;
        $image = curl_file_create($img_path, 'image/jpg');
        $curl = self::curl();
        $ret = $curl->post($url, [
            'media' => $image
        ]);

        $info = json_decode($ret, true);
        if($info['errcode'])
        {
            echo $info['errmsg'];
            return false;
        }
        else
        {
            $info['upload_time'] = time();
            file_put_contents(self::$file_file, json_encode($info));
            return $info['url'];
        }
    }

    public static function get_openids($next_openid = '')
    {
        $access_token = self::get_access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$access_token.'&next_openid='.$next_openid;
        $curl = self::curl();
        $ret = $curl->get($url);
        return $ret;
    }
}