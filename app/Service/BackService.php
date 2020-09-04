<?php
namespace App\Service;

use Hyperf\Utils\ApplicationContext;

class BackService
{
    public static $instance;

    /**
     * 通过延迟加载（用到时才加载）获取实例
     * @return self
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * @param $pid
     * 处理数组
     */
    function makeArray($pid,$id){
        $arp = explode(',',$pid);
        $data = array();
        if(is_array($arp) && in_array($id, $arp)) {
            $data = array_diff($arp, [$id]);
            if (count($data) > 0) return implode(',', $data);
            return $data[0];
        }
        return false;
    }
}