<?php
if ( ! defined( 'ABSPATH' ) ) {
    define('ABSPATH', dirname(__DIR__, 1));
}
const GITUSERNAME = 'tao1442605868:taotao0627';//git用户名：密码
$params = file_get_contents("php://input");
$ids = !empty($params) ? json_decode($params,true):'';
$ids = !empty($_REQUEST['domain']) && is_array($ids) ? array_merge($ids,$_REQUEST):$ids;
if(isset($ids['repository']['url'])) {
    $extPath = ABSPATH;
    if(isset($ids['domain']) && $ids['domain']) {
        //todo::下垃前台
        // file_put_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . "./log.txt", date("Y-m-d H:i:s") . "  " . var_export($ids,true) . "\r\n", FILE_APPEND);
        $forepath = $extPath . '/' . $ids['domain'];//todo
        $lj = $ids['domain'];
        if (is_dir($forepath)) {
            //下拉
            $clonePath = $extPath . '/githook/pull.sh';//todo
            $cmd = "{$clonePath} {$forepath} {$extPath}";//克隆仓库代码到指定文件夹
        } else {
            //克隆
            $getUrl = explode('@', $ids['repository']['url']);
            $rep_url = str_replace(':', '/', $getUrl[1]);
            $clone_url = 'https://' . GITUSERNAME . '@' . $rep_url;
            $clonePath = $extPath . '/githook/fg_clone.sh';//todo
            $pubpath = $extPath;//todo
            $cmd = "{$clonePath} {$pubpath} {$clone_url} {$extPath} {$lj}";//克隆仓库代码到指定文件夹
        }
        shell_exec($cmd);
        //复制index->resources模板
        $dist = $lj == 'foreground' ? 'dist' : $ids['domain'];
        $cpPath = $extPath . '/githook/fg_cp.sh';//todo
        $pdpath = $extPath . '/public/' . $dist;//todo
        $respath = $extPath . '/public/view';//todo
        //$respath = $extPath . '/resources/views';//todo::laravel框架
        $public = $extPath . '/public';//todo
        $lj = $lj == 'foreground' ? 'index' : $ids['domain'];
        $cpcmd = "{$cpPath} {$forepath} {$public} {$pdpath} {$respath} {$extPath} {$lj} {$dist}";//cp文件替换文件
        shell_exec($cpcmd);
    }else{
        //todo::下垃后台
        //file_put_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . "./log2.txt", date("Y-m-d H:i:s") . "  " . var_export($ids,true) . "\r\n", FILE_APPEND);
        $clonePath = ABSPATH .'/githook/pull.sh';
        $forepath = ABSPATH;
        $cmd = "{$clonePath} {$forepath} {$forepath}";
        shell_exec($cmd);
    }
}else{
    exit('没有参数');
}