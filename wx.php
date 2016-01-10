<?php

require_once __DIR__.'/Curl/autoload.php';

use Curl\Curl;

class Wx
{
    protected static $access_token_log = __DIR__.'/data/access.log';
    protected static $msg_file = __DIR__.'/data/msg.log';
    protected static $file_file = __DIR__.'/data/file.log';
    protected static $media_file = __DIR__.'/data/media.log';
    protected static $media_forever_file = __DIR__.'/data/forever_media.log';

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

    public static function upload_media($media_info, $type = 'image')
    {
        $access_token = self::get_access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$access_token.'&type='.$type;
        $media = curl_file_create($media_info['path'], $media_info['type']);
        $curl = self::curl();
        $ret = $curl->post($url, [
            'media' => $media
        ]);
        file_put_contents(self::$media_file, $ret, FILE_APPEND);
        return $ret;
    }

    public static function get_media($media_id)
    {
        $access_token = self::get_access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$media_id;
        $curl = self::curl();
        $ret = $curl->download($url, '/tmp/'.$media_id);
        return $ret;
    }

    public static function add_material()
    {
        $access_token = self::get_access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$access_token;
        $curl = self::curl();
        $ret = $curl->post($url, [
            'type' => 'image',
            'media' => '@/Users/alusa/Downloads/2.jpg'
        ]);
        file_put_contents(self::$media_forever_file, $ret, FILE_APPEND);
        return $ret;
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

    public static function get_material_count()
    {
        $access_token = self::get_access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token='.$access_token;
        $curl = self::curl();
        $ret = $curl->get($url);
        return $ret;
    }

    public static function upload_news()
    {
        $access_token = self::get_access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$access_token;
    }

    public static function send_message()
    {
        $access_token = self::get_access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$access_token;
        $send_data = [
            'filter' => [
                'is_to_all' => false,
                'group_id' => 100
            ],
            'text' => [
                'content'=>'<h1>Hello World</h1>'
            ],
            'msgtype' => 'text'
        ];

        $curl = self::curl();
        $ret = $curl->post($url, json_encode($send_data));
        return $ret;
    }

    public static function create_group()
    {
        $access_token = self::get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/groups/create?access_token=".$access_token;
        $curl = self::curl();
        $group = [
            'group' => [
                'name' => '分组2'
            ]
        ];
        $ret = $curl->post($url, json_encode($group) );
        return $ret;
    }

    public static function move_user_to_group()
    {
        $access_token = self::get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=".$access_token;
        $curl = self::curl();
        $send_data = [
            'openid' => 'ogY7UjteXzwqF39bascYoX2vodsM',
            'to_groupid' => 100
        ];
        $ret = $curl->post($url, json_encode($send_data) );
        return $ret;
    }

    public static function set_industry_no()
    {
        $access_token = self::get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=".$access_token;
        $curl = self::curl();
        $send_data = [
            'industry_id1' => 9,
            'industry_id2' => 1
        ];
        $ret = $curl->post($url, json_encode($send_data) );
        return $ret;
    }

    public static function add_tpl()
    {
        $access_token = self::get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=".$access_token;
        $curl = self::curl();
        $send_data = [
            'template_id_short' => 'TM00015'
        ];
        $ret = $curl->post($url, json_encode($send_data) );
        return $ret;
    }

    public static function send_tpl_message($openid)
    {
        $tpl_id = 'hAzsbVfuLl_lf-DUFlev-Ibp5rNCzI-F88feFusbgmM';
        $access_token = self::get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
        $curl = self::curl();
        $send_data = [
            'touser' => $openid,
            'template_id' => $tpl_id,
            'url' => '',
            'data' => [
                'first' => [
                    'value' => '恭喜你购买成功！',
                    "color" => "#173177",
                ],
                'keynote1' => [
                    "value"=>"巧克力",
                    "color"=>"#173177"
                ],
                "keynote2"=> [
                    "value"=>"39.8元",
                    "color"=>"#173177"
                ],
                "keynote3"=>[
                   "value"=>"2016年01月10日",
                   "color"=>"#173177",
                ],
               "remark"=> [
                   "value" => "欢迎再次购买！",
                   "color"=>"#173177"
               ]
            ]
        ];
        $ret = $curl->post($url, json_encode($send_data) );
        return $ret;

    }



}