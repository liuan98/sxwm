<?php
if(! function_exists('mbStrSplit')){

    /**
     * @param $string
     * @param int $len
     * @return array
     * 截取函数
     */
    function mbStrSplit ($string, $len=1) {
        $start = 0;
        $strlen = mb_strlen($string);
        $array = array();
        while ($strlen) {
            $array[] = mb_substr($string,$start,$len,"utf8");
            $string = mb_substr($string, $len, $strlen,"utf8");
            $strlen = mb_strlen($string);
        }
        return $array;
    }
}
if(! function_exists('curl_file_get_contents')) {

    function curl_file_get_contents($geturl)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $geturl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Cookie: BIDUPSID=E5BF7827D1E495BC877E70EF216D3B96; PSTM=1573007792; BAIDUID=E5BF7827D1E495BCB6AC00573F32D347:FG=1; delPer=0; BD_CK_SAM=1; PSINO=7; H_PS_PSSID=1466_21120_29074_29567_29699_29220_22157; BDSVRTM=15",
                "Host: www.baidu.com",
                "User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.87 Safari/537.36",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }
}
    if(! function_exists('filter_mark')) {
        function filter_mark($text)
        {
            if (trim($text) == '') return '';
            $text = preg_replace("/[[:punct:]\s]/", ' ', $text);
            $text = urlencode($text);
            $text = preg_replace("/(%7E|%60|%21|%40|%23|%24|%25|%5E|%26|%27|%2A|%28|%29|%2B|%7C|%5C|%3D|\-|_|%5B|%5D|%7D|%7B|%3B|%22|%3A|%3F|%3E|%3C|%2C|\.|%2F|%A3%BF|%A1%B7|%A1%B6|%A1%A2|%A1%A3|%A3%AC|%7D|%A1%B0|%A3%BA|%A3%BB|%A1%AE|%A1%AF|%A1%B1|%A3%FC|%A3%BD|%A1%AA|%A3%A9|%A3%A8|%A1%AD|%A3%A4|%A1%A4|%A3%A1|%E3%80%82|%EF%BC%81|%EF%BC%8C|%EF%BC%9B|%EF%BC%9F|%EF%BC%9A|%E3%80%81|%E2%80%A6%E2%80%A6|%E2%80%9D|%E2%80%9C|%E2%80%98|%E2%80%99|%EF%BD%9E|%EF%BC%8E|%EF%BC%88)+/", ' ', $text);
            $text = urldecode($text);
            return trim($text);
        }
    }

if(! function_exists('async_get_url')) {
    /**
     * @param $url_array
     * @param int $wait_usec
     * @return array|bool
     * 多线程
     */
    function async_get_url($url_array, $wait_usec = 0)
    {
        if (!is_array($url_array))
            return false;
        $wait_usec = intval($wait_usec);
        $data = array();
        $handle = array();
        $running = 0;
        $mh = curl_multi_init(); // multi curl handler
        $i = 0;
        foreach ($url_array as $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url['url']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return don't print
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.87 Safari/537.36');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 302 redirect
            curl_setopt($ch, CURLOPT_MAXREDIRS, 7);
            curl_multi_add_handle($mh, $ch); // 把 curl resource 放进 multi curl handler 里
            $handle[$i]['ch_url'] = $ch;
            $handle[$i]['ch_val'] = $url['val'];
            $i++;
        }
        /* 执行 */
        do {
            curl_multi_exec($mh, $running);
            if ($wait_usec > 0) /* 每个 connect 要间隔多久 */
                usleep($wait_usec); // 250000 = 0.25 sec
        } while ($running > 0);
        /* 读取资料 */
        foreach ($handle as $i => $ch) {
            $content = curl_multi_getcontent($ch['ch_url']);
            $data[$i]['url'] = (curl_errno($ch['ch_url']) == 0) ? $content : false;
            $data[$i]['val'] = $ch['ch_val'];
        }
        /* 移除 handle*/
        foreach ($handle as $ch) {
            curl_multi_remove_handle($mh, $ch['ch_url']);
        }
        curl_multi_close($mh);
        return $data;
    }
}
if(! function_exists('uploadImg')) {
    function uploadImg($file)
    {
        $path = 'storage/images/';
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $file, $result)) {
            $type = $result[2];
            if (!file_exists($path)) {
                mkdir($path, 0700);
            }
            $img = date('YmdHis',time()).'_'.uniqid() . ".{$type}";
            $new_file = $path.$img;
            //$ddd = base_path().'\public\upload\images'.DIRECTORY_SEPARATOR.$img;var_dump($ddd);
            //$ddd = storage_path().'/app/public/images'.DIRECTORY_SEPARATOR.$img;
            $ddd = public_path().'/storage/images/'.$img;
            if (file_put_contents($ddd, base64_decode(str_replace($result[1], '', $file)))) {
                /*这里是数据库操作*/
                return $new_file;
            } else {
                return false;;
            }
        } else {
            return false;
        }
    }
}
if(! function_exists('oneday')) {
    function oneDay()
    {
        $today = date("Y-m-d", time());
        $y = date("Y");
        $m = date("m");
        $d = date("d");
        $dayTime = mktime(0, 0, 0, $m, $d, $y);//今天凌晨的时间戳
        $day = date('Y-m-d', $dayTime);//今天的时间2018/10/16

        return $day;
    }
}
if(! function_exists('oneweek')) {
    function oneweek()
    {
        //当前日期
        $sdefaultDate = date("Y-m-d");
        //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
        $first = 1;
        //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        $w = date('w', strtotime($sdefaultDate));
        //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $week_start = date('Y-m-d', strtotime("$sdefaultDate -" . ($w ? $w - $first : 6) . ' days'));
        //本周结束日期
        $week_end = date('Y-m-d', strtotime("$week_start +6 days"));
        return array($week_start,$week_end);
    }
}
if(! function_exists('onemonth')) {
    function onemonth()
    {
        //当前日期
        $month_start = $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
        $month_end = date('Y-m-d', strtotime("$month_start +1 month -1 day"));

        return array($month_start,$month_end);
    }
}
if(! function_exists('objectToArray')) {
    function objectToArray($object)
    {
        //先编码成json字符串，再解码成数组
        return json_decode(json_encode($object), true);
    }
}
if(! function_exists('getAccessToken')) {
    function getAccessToken($appkey, $appsecret)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://oapi.dingtalk.com/gettoken?appkey=$appkey&appsecret=$appsecret",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Host: oapi.dingtalk.com",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return '';
//            echo "cURL Error #:" . $err;
        } else {
             $response = json_decode($response,true);
        }
        if ($response['errcode'] != 0) {
            //Log::e('获取access_token错误，' . $ret->errmsg);
            return '';
        }
        return isset($response['access_token'])?$response['access_token']:'';
    }
}

if(! function_exists('GetRequest')) {

    function GetRequest($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Host: oapi.dingtalk.com",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return '';
//            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response,true);
        }
        return $response;
    }
}

if(! function_exists('PostRequest')) {

    function PostRequest($url,$data)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
//                "Host: oapi.dingtalk.com",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return '';
//            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response,true);
        }
        return $response;
    }
}


if(!function_exists('jsonToArray')){
    function jsonToArray($ids)
    {
        $ids = htmlspecialchars_decode(str_replace('\\', '', $ids));
        $ids = @json_decode($ids, true);
        return $ids;
    }
}
if(! function_exists('authcode')) {
    function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
    {
        $ckey_length = 4;
        $key = md5($key != '' ? $key : $GLOBALS['_W']['config']['setting']['authkey']);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if ($operation == 'DECODE') {
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc . str_replace('=', '', base64_encode($result));
        }

    }
}
if(! function_exists('success')) {
    function success($result, $message = 'ok')
    {
        return ['code' => 200, 'data' => $result, 'message' => $message];
    }
}
if(! function_exists('fail')) {
    function fail($message)
    {
        return ['code' => -1, 'message' => $message];
    }
}
if(! function_exists('random')) {
    function random($length, $numeric = FALSE)
    {
        $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
        if ($numeric) {
            $hash = '';
        } else {
            $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
            $length--;
        }
        $max = strlen($seed) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $seed[mt_rand(0, $max)];
        }
        return $hash;
    }
}
if(! function_exists('get_week_arr')) {
    function get_week_arr()
    {

        //获取今天是周几，0为周日
        $this_week_num = date('w');

        $timestamp = time();
        //如果获取到的日期是周日，需要把时间戳换成上一周的时间戳
        //英语国家 一周的开始时间是周日
        if ($this_week_num == 0) {
            $timestamp = $timestamp - 86400;
        }

        $this_week_arr = [
            [
                'is_sign' => 0,
                'this_week' => 1,
                'week_name' => '星期一',
                'week_time' => strtotime(date('Y-m-d', strtotime("this week Monday", $timestamp))),
                'week_date' => date('Y-m-d', strtotime("this week Monday", $timestamp)),
            ],
            [
                'is_sign' => 0,
                'this_week' => 2,
                'week_name' => '星期二',
                'week_time' => strtotime(date('Y-m-d', strtotime("this week Tuesday", $timestamp))),
                'week_date' => date('Y-m-d', strtotime("this week Tuesday", $timestamp)),
            ],
            [
                'is_sign' => 0,
                'this_week' => 3,
                'week_name' => '星期三',
                'week_time' => strtotime(date('Y-m-d', strtotime("this week Wednesday", $timestamp))),
                'week_date' => date('Y-m-d', strtotime("this week Wednesday", $timestamp)),
            ],
            [
                'is_sign' => 0,
                'this_week' => 4,
                'week_name' => '星期四',
                'week_time' => strtotime(date('Y-m-d', strtotime("this week Thursday", $timestamp))),
                'week_date' => date('Y-m-d', strtotime("this week Thursday", $timestamp)),
            ],
            [
                'is_sign' => 0,
                'this_week' => 5,
                'week_name' => '星期五',
                'week_time' => strtotime(date('Y-m-d', strtotime("this week Friday", $timestamp))),
                'week_date' => date('Y-m-d', strtotime("this week Friday", $timestamp)),
            ],
            [
                'is_sign' => 0,
                'this_week' => 6,
                'week_name' => '星期六',
                'week_time' => strtotime(date('Y-m-d', strtotime("this week Saturday", $timestamp))),
                'week_date' => date('Y-m-d', strtotime("this week Saturday", $timestamp)),
            ],
            [
                'is_sign' => 0,
                'this_week' => 7,
                'week_name' => '星期天',
                'week_time' => strtotime(date('Y-m-d', strtotime("this week Sunday", $timestamp))),
                'week_date' => date('Y-m-d', strtotime("this week Sunday", $timestamp)),
            ],
        ];
        return $this_week_arr;
    }
    if(! function_exists('httpRequest')) {
        function httpRequest($url, $post_data = '', $method = 'GET')
        {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
            if ($method == 'POST') {
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($post_data != '') {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
                }
            }

            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($curl);
            curl_close($curl);
            return $result;
        }
    }
    if(! function_exists('formatBytes')) {
        function formatBytes($size)
        {
            $units = array(' B', ' KB', ' MB', ' GB', ' TB');
            for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
            return round($size, 2) . $units[$i];
        }
    }
    /**
     *
     * 验证码
     */
    if(! function_exists('randcode')) {
        function randcode($length)
        {
            $key = '';
            $pattern = '1234567890';
            for ($i = 0; $i < $length; $i++) {
                $key .= $pattern[mt_rand(0, 9)];
            }
            return $key;
        }
    }

    /**
     * 谷歌翻译
     */
    if(! function_exists('getGoogle')) {
        function getGoogle($text,$to='zh-CN'){
            $entext = urlencode($text);
            $url = 'https://translate.google.cn/translate_a/single?client=gtx&dt=t&ie=UTF-8&oe=UTF-8&sl=auto&tl='.$to.'&q='.$entext;
            set_time_limit(0);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS,20);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 40);
            curl_setopt($ch, CURLOPT_URL, $url);
            $result = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($result);
            if(!empty($result)){
                foreach($result[0] as $k){
                    $v[] = $k[0];
                }
                return implode(" ", $v);
            }
        }
    }

    //谷歌经纬度逆解析
    if(! function_exists('urlGoogle')) {
        function urlGoogle($latlng){
            $list = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$latlng.'&key=AIzaSyBZxAbfgeDc2z6YUOaBs8b0NuQgm_cHLdw&language=pt-br';
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $list);
            curl_setopt($curl, CURLOPT_HEADER, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
            $data = curl_exec($curl);
            curl_close($curl);
            return $data;
        }
    }

}

