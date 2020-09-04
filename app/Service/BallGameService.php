<?php
namespace App\Service;


use Hyperf\Utils\ApplicationContext;
use phpQuery;
class BallGameService
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
     * bet365 球赛数据
     */
    public function betBall(){
        $redis = ApplicationContext::getContainer()->get(\Redis::class);
        //$redis->del('betBallData');
        $betBallData = $redis->get('betBallData');
        if (!$betBallData) {
            $url = 'http://op1.win007.com/company.aspx?id=281&company=bet%20365(%D3%A2%B9%FA)';
            phpQuery::newDocumentFile($url);
            $data = array();$data2= array();$data3 = array();$data4 = array();
            $i = 0;
            $j = 0;
            $jj = 0;
            foreach (pq('#table_schedule tr') as $key => $val) {
                if ((pq($val)->attr('id')) != '' && (pq($val)->attr('name')) != '') {
                    foreach (pq($val)->find('td') as $key2 => $val2) {
                        if ($key2 == 1) {
                            $t = pq($val2)->find('a')->text();
                            $t = mb_convert_encoding($t, 'ISO-8859-1', 'utf-8');
                            $t = mb_convert_encoding($t, 'utf-8', 'GBK');
                            $t1 = pq($val2)->text();
                            $t1 = mb_convert_encoding($t1, 'ISO-8859-1', 'utf-8');
                            $t1 = mb_convert_encoding($t1, 'utf-8', 'GBK');
                            $data[$i]['name'] = $t ? $t : $t1;
                        }
                        if ($key2 == 2) {
                            $te = str_replace('<br>', ' ', pq($val2)->html());
                            $data[$i]['time'] = $te;
                        }
                        if ($key2 == 3) {
                            $t = pq($val2)->find('a')->text();
                            $t = mb_convert_encoding($t, 'ISO-8859-1', 'utf-8');
                            $t = mb_convert_encoding($t, 'utf-8', 'GBK');
                            $data[$i]['z_name'] = $t;
                        }
                        if ($key2 == 4) {
                            $data[$i]['c_num1'] = pq($val2)->text();
                        }
                        if ($key2 == 5) {
                            $data[$i]['c_num2'] = pq($val2)->text();
                        }
                        if ($key2 == 6) {
                            $data[$i]['c_num3'] = pq($val2)->text();
                        }
                        if ($key2 == 11) {
                            $t = pq($val2)->find('a')->text();
                            $t = mb_convert_encoding($t, 'ISO-8859-1', 'utf-8');
                            $t = mb_convert_encoding($t, 'utf-8', 'GBK');
                            $data[$i]['k_name'] = $t;
                        }
                    }
                    $i++;
                }
                if ((pq($val)->attr('id')) != '' && (pq($val)->attr('name')) == '') {
                    foreach (pq($val)->find('td') as $key3 => $val3) {
                        if ($key3 == 0) {
                            $data[$j]['j_num1'] = pq($val3)->text();
                        }
                        if ($key3 == 1) {
                            $data[$j]['j_num2'] = pq($val3)->text();
                        }
                        if ($key3 == 2) {
                            $data[$j]['j_num3'] = pq($val3)->text();
                        }
                    }
                    $data[$j]['time2'] = $data[$j]['time'];
                    $data[$j]['time'] = date('H:i', strtotime('20' . $data[$j]['time'] . ':00'));
                    if ($data[$j]['j_num3'] == '') {
                        unset($data[$j]);
                    }
                    if ($data[$j]['j_num1'] < $data[$j]['c_num1'] && $data[$j]['j_num2'] > $data[$j]['c_num2'] && $data[$j]['j_num3'] > $data[$j]['c_num3']) {
                        $data2[$j]['name'] = $data[$j]['name'];
                        $data2[$j]['time'] = $data[$j]['time'];
                        $data2[$j]['time2'] = $data[$j]['time2'];
                        $data2[$j]['z_name'] = $data[$j]['z_name'];
                        $data2[$j]['c_num1'] = $data[$j]['c_num1'];
                        $data2[$j]['c_num2'] = $data[$j]['c_num2'];
                        $data2[$j]['c_num3'] = $data[$j]['c_num3'];
                        $data2[$j]['k_name'] = $data[$j]['k_name'];
                        $data2[$j]['j_num1'] = $data[$j]['j_num1'];
                        $data2[$j]['j_num2'] = $data[$j]['j_num2'];
                        $data2[$j]['j_num3'] = $data[$j]['j_num3'];
                    }
                    if ($data[$j]['j_num1'] > $data[$j]['c_num1']  && $data[$j]['j_num2'] > $data[$j]['c_num2'] && $data[$j]['j_num3'] < $data[$j]['c_num3']) {
                        $data2[$j]['name'] = $data[$j]['name'];
                        $data2[$j]['time'] = $data[$j]['time'];
                        $data2[$j]['time2'] = $data[$j]['time2'];
                        $data2[$j]['z_name'] = $data[$j]['z_name'];
                        $data2[$j]['c_num1'] = $data[$j]['c_num1'];
                        $data2[$j]['c_num2'] = $data[$j]['c_num2'];
                        $data2[$j]['c_num3'] = $data[$j]['c_num3'];
                        $data2[$j]['k_name'] = $data[$j]['k_name'];
                        $data2[$j]['j_num1'] = $data[$j]['j_num1'];
                        $data2[$j]['j_num2'] = $data[$j]['j_num2'];
                        $data2[$j]['j_num3'] = $data[$j]['j_num3'];
                    }

                    if($data2[$j]) {
                        $mktime = 10 * 60;
                        if ((time() - $mktime) > strtotime($data[$j]['time'])) {
                            $data3[$j]['name'] = $data2[$j]['name'];
                            $data3[$j]['time'] = $data2[$j]['time'];
                            $data3[$j]['z_name'] = $data2[$j]['z_name'];
                            $data3[$j]['c_num1'] = $data2[$j]['c_num1'];
                            $data3[$j]['c_num2'] = $data2[$j]['c_num2'];
                            $data3[$j]['c_num3'] = $data2[$j]['c_num3'];
                            $data3[$j]['k_name'] = $data2[$j]['k_name'];
                            $data3[$j]['j_num1'] = $data2[$j]['j_num1'];
                            $data3[$j]['j_num2'] = $data2[$j]['j_num2'];
                            $data3[$j]['j_num3'] = $data2[$j]['j_num3'];
                        } else {
                            $data4[$j]['name'] = $data2[$j]['name'];
                            $data4[$j]['time'] = $data2[$j]['time'];
                            $data4[$j]['z_name'] = $data2[$j]['z_name'];
                            $data4[$j]['c_num1'] = $data2[$j]['c_num1'];
                            $data4[$j]['c_num2'] = $data2[$j]['c_num2'];
                            $data4[$j]['c_num3'] = $data2[$j]['c_num3'];
                            $data4[$j]['k_name'] = $data2[$j]['k_name'];
                            $data4[$j]['j_num1'] = $data2[$j]['j_num1'];
                            $data4[$j]['j_num2'] = $data2[$j]['j_num2'];
                            $data4[$j]['j_num3'] = $data2[$j]['j_num3'];
                        }
                    }

                    $j++;
                }

                if ((pq($val)->attr('id')) == '' && (pq($val)->attr('name')) == '') {
                    $jj++;
                }
                if($jj > 3){
                    break;
                }
            }
            array_values($data4);
            array_values($data3);
            $info = array_merge($data4, $data3);
            if ($info) {
                $betBallData = serialize(array_values($info));
                $redis->set('betBallData', $betBallData);
                $redis->expire('betBallData', 180);
            }
        }
        unset($data2,$data,$data4, $data3);
        return unserialize($betBallData);
    }

    /**
     * 威廉希尔 球赛数据
     */
    public function wlxeBall(){
        $redis = ApplicationContext::getContainer()->get(\Redis::class);
        //$redis->del('wlxeBall');
        $wlxeBall = $redis->get('wlxeBall');
        if (!$wlxeBall) {
            $url = 'http://op1.win007.com/company.aspx?id=115&company=%CD%FE%C1%AE%CF%A3%B6%FB(%D3%A2%B9%FA)';
            phpQuery::newDocumentFile($url);
            $data = array();$data2=array();$data3=array();$data4=array();
            $i = 0;
            $j = 0;
            $jj = 0;
            foreach (pq('#table_schedule tr') as $key => $val) {
                if ((pq($val)->attr('id')) != '' && (pq($val)->attr('name')) != '') {
                    foreach (pq($val)->find('td') as $key2 => $val2) {
                        if ($key2 == 1) {
                            $t = pq($val2)->find('a')->text();
                            $t = mb_convert_encoding($t, 'ISO-8859-1', 'utf-8');
                            $t = mb_convert_encoding($t, 'utf-8', 'GBK');
                            $t1 = pq($val2)->text();
                            $t1 = mb_convert_encoding($t1, 'ISO-8859-1', 'utf-8');
                            $t1 = mb_convert_encoding($t1, 'utf-8', 'GBK');
                            $data[$i]['name'] = $t?$t:$t1;
                        }
                        if ($key2 == 2) {
                            $te = str_replace('<br>', ' ', pq($val2)->html());
                            $data[$i]['time'] = $te;
                        }
                        if ($key2 == 3) {
                            $t = pq($val2)->find('a')->text();
                            $t = mb_convert_encoding($t, 'ISO-8859-1', 'utf-8');
                            $t = mb_convert_encoding($t, 'utf-8', 'GBK');
                            $data[$i]['z_name'] = $t;
                        }
                        if ($key2 == 4) {
                            $data[$i]['c_num1'] = pq($val2)->text();
                        }
                        if ($key2 == 5) {
                            $data[$i]['c_num2'] = pq($val2)->text();
                        }
                        if ($key2 == 6) {
                            $data[$i]['c_num3'] = pq($val2)->text();
                        }
                        if ($key2 == 11) {
                            $t = pq($val2)->find('a')->text();
                            $t = mb_convert_encoding($t, 'ISO-8859-1', 'utf-8');
                            $t = mb_convert_encoding($t, 'utf-8', 'GBK');
                            $data[$i]['k_name'] = $t;
                        }
                    }
                    $i++;
                }
                if ((pq($val)->attr('id')) != '' && (pq($val)->attr('name')) == '') {
                    foreach (pq($val)->find('td') as $key3 => $val3) {
                        if ($key3 == 0) {
                            $data[$j]['j_num1'] = pq($val3)->text();
                        }
                        if ($key3 == 1) {
                            $data[$j]['j_num2'] = pq($val3)->text();
                        }
                        if ($key3 == 2) {
                            $data[$j]['j_num3'] = pq($val3)->text();
                        }
                    }
//                    $mktime = 10 * 60;
//                    if ((time() - $mktime) > strtotime('20' . $data[$j]['time'] . ':00')) {
//                        unset($data[$j]);
//                    } else {
//                        $data[$j]['time'] = date('H:i', strtotime('20' . $data[$j]['time'] . ':00'));
//                    }
                    $data[$j]['time2'] = '20' . $data[$j]['time'] . ':00';
                    $data[$j]['time'] = date('H:i', strtotime('20' . $data[$j]['time'] . ':00'));
                    if ($data[$j]['j_num3']=='') {
                        unset($data[$j]);
                    }
                    if ($data[$j]['j_num1'] < $data[$j]['c_num1'] && $data[$j]['j_num2'] > $data[$j]['c_num2'] && $data[$j]['j_num3'] > $data[$j]['c_num3']) {
                        $data2[$j]['name'] = $data[$j]['name'];
                        $data2[$j]['time'] = $data[$j]['time'];
                        $data2[$j]['time2'] = $data[$j]['time2'];
                        $data2[$j]['z_name'] = $data[$j]['z_name'];
                        $data2[$j]['c_num1'] = $data[$j]['c_num1'];
                        $data2[$j]['c_num2'] = $data[$j]['c_num2'];
                        $data2[$j]['c_num3'] = $data[$j]['c_num3'];
                        $data2[$j]['k_name'] = $data[$j]['k_name'];
                        $data2[$j]['j_num1'] = $data[$j]['j_num1'];
                        $data2[$j]['j_num2'] = $data[$j]['j_num2'];
                        $data2[$j]['j_num3'] = $data[$j]['j_num3'];
                    }
                    if ($data[$j]['j_num1'] > $data[$j]['c_num1']  && $data[$j]['j_num2'] > $data[$j]['c_num2'] && $data[$j]['j_num3'] < $data[$j]['c_num3']) {
                        $data2[$j]['name'] = $data[$j]['name'];
                        $data2[$j]['time'] = $data[$j]['time'];
                        $data2[$j]['time2'] = $data[$j]['time2'];
                        $data2[$j]['z_name'] = $data[$j]['z_name'];
                        $data2[$j]['c_num1'] = $data[$j]['c_num1'];
                        $data2[$j]['c_num2'] = $data[$j]['c_num2'];
                        $data2[$j]['c_num3'] = $data[$j]['c_num3'];
                        $data2[$j]['k_name'] = $data[$j]['k_name'];
                        $data2[$j]['j_num1'] = $data[$j]['j_num1'];
                        $data2[$j]['j_num2'] = $data[$j]['j_num2'];
                        $data2[$j]['j_num3'] = $data[$j]['j_num3'];
                    }

                    if($data2[$j]) {
                        $mktime = 10 * 60;
                        if ((time() - $mktime) > strtotime($data2[$j]['time2'])) {
                            $data3[$j]['name'] = $data2[$j]['name'];
                            $data3[$j]['time'] = $data2[$j]['time'];
                            $data3[$j]['z_name'] = $data2[$j]['z_name'];
                            $data3[$j]['c_num1'] = $data2[$j]['c_num1'];
                            $data3[$j]['c_num2'] = $data2[$j]['c_num2'];
                            $data3[$j]['c_num3'] = $data2[$j]['c_num3'];
                            $data3[$j]['k_name'] = $data2[$j]['k_name'];
                            $data3[$j]['j_num1'] = $data2[$j]['j_num1'];
                            $data3[$j]['j_num2'] = $data2[$j]['j_num2'];
                            $data3[$j]['j_num3'] = $data2[$j]['j_num3'];
                        } else {
                            $data4[$j]['name'] = $data2[$j]['name'];
                            $data4[$j]['time'] = $data2[$j]['time'];
                            $data4[$j]['z_name'] = $data2[$j]['z_name'];
                            $data4[$j]['c_num1'] = $data2[$j]['c_num1'];
                            $data4[$j]['c_num2'] = $data2[$j]['c_num2'];
                            $data4[$j]['c_num3'] = $data2[$j]['c_num3'];
                            $data4[$j]['k_name'] = $data2[$j]['k_name'];
                            $data4[$j]['j_num1'] = $data2[$j]['j_num1'];
                            $data4[$j]['j_num2'] = $data2[$j]['j_num2'];
                            $data4[$j]['j_num3'] = $data2[$j]['j_num3'];
                        }
                    }

                    $j++;
                }

                if ((pq($val)->attr('id')) == '' && (pq($val)->attr('name')) == '') {
                    $jj++;
                }
                if($jj > 3){
                    break;
                }
            }
            array_values($data4);
            array_values($data3);
            $info = array_merge($data4, $data3);
            if ($info) {
                $wlxeBall = serialize(array_values($info));
                $redis->set('wlxeBall', $wlxeBall);
                $redis->expire('wlxeBall', 180);
            }
        }
        unset($data2,$data,$data4, $data3);
        return unserialize($wlxeBall);
    }
    /**
     * 对比数据
     */
    public function compareBall()
    {
        $data = array();
        //bet365
        $betBall = self::betBall();
        $countBetBall = count($betBall);
        //威廉希尔
        $wlxeBall = self::wlxeBall();
        $info = array_merge($betBall, $wlxeBall);
        if ($betBall && $wlxeBall) {
            foreach ($info as $key => $val) {
                $b = $key < $countBetBall ? 'b' : 'w';
                $data[$val['z_name'] . '_' . $val['k_name']][$b]['name'] = $val['name'];
                $data[$val['z_name'] . '_' . $val['k_name']][$b]['time'] = $val['time'];
                $data[$val['z_name'] . '_' . $val['k_name']][$b]['z_name'] = $val['z_name'];
                $data[$val['z_name'] . '_' . $val['k_name']][$b]['c_num1'] = $val['c_num1'];
                $data[$val['z_name'] . '_' . $val['k_name']][$b]['c_num2'] = $val['c_num2'];
                $data[$val['z_name'] . '_' . $val['k_name']][$b]['c_num3'] = $val['c_num3'];
                $data[$val['z_name'] . '_' . $val['k_name']][$b]['k_name'] = $val['k_name'];
                $data[$val['z_name'] . '_' . $val['k_name']][$b]['j_num1'] = $val['j_num1'];
                $data[$val['z_name'] . '_' . $val['k_name']][$b]['j_num2'] = $val['j_num2'];
                $data[$val['z_name'] . '_' . $val['k_name']][$b]['j_num3'] = $val['j_num3'];
            }
        }
        $data2 = array();
        //$i = 0;
        //对数组进行处理
        foreach ($data as $val2) {
            if(isset($val2['w']['j_num1']) && isset($val2['b']['j_num1']) && ($val2['w']['j_num1'] < $val2['w']['c_num1']) && ($val2['b']['j_num1'] < $val2['b']['c_num1']) && ($val2['w']['j_num1'] < $val2['b']['j_num1'])) {
                $data2[$val2['b']['name']]['b']['name'] = $val2['b']['name'];
                $data2[$val2['b']['name']]['b']['time'] = $val2['b']['time'];
                $data2[$val2['b']['name']]['b']['z_name'] = $val2['b']['z_name'];
                $data2[$val2['b']['name']]['b']['c_num1'] = $val2['b']['c_num1'];
                $data2[$val2['b']['name']]['b']['c_num2'] = $val2['b']['c_num2'];
                $data2[$val2['b']['name']]['b']['c_num3'] = $val2['b']['c_num3'];
                $data2[$val2['b']['name']]['b']['k_name'] = $val2['b']['k_name'];
                $data2[$val2['b']['name']]['b']['j_num1'] = $val2['b']['j_num1'];
                $data2[$val2['b']['name']]['b']['j_num2'] = $val2['b']['j_num2'];
                $data2[$val2['b']['name']]['b']['j_num3'] = $val2['b']['j_num3'];
                $data2[$val2['b']['name']]['w']['name'] = $val2['w']['name'];
                $data2[$val2['b']['name']]['w']['time'] = $val2['w']['time'];
                $data2[$val2['b']['name']]['w']['z_name'] = $val2['w']['z_name'];
                $data2[$val2['b']['name']]['w']['c_num1'] = $val2['w']['c_num1'];
                $data2[$val2['b']['name']]['w']['c_num2'] = $val2['w']['c_num2'];
                $data2[$val2['b']['name']]['w']['c_num3'] = $val2['w']['c_num3'];
                $data2[$val2['b']['name']]['w']['k_name'] = $val2['w']['k_name'];
                $data2[$val2['b']['name']]['w']['j_num1'] = $val2['w']['j_num1'];
                $data2[$val2['b']['name']]['w']['j_num2'] = $val2['w']['j_num2'];
                $data2[$val2['b']['name']]['w']['j_num3'] = $val2['w']['j_num3'];
                $data2[$val2['b']['name']]['time2'] = $val2['b']['time'];
                //$i++;
            }
            if(isset($val2['w']['j_num3']) && isset($val2['b']['j_num3']) && ($val2['w']['j_num3'] < $val2['w']['c_num3']) && ($val2['b']['j_num3'] < $val2['b']['c_num3']) && ($val2['w']['j_num3'] < $val2['b']['j_num3'])){
                $data2[$val2['b']['name']]['b']['name'] = $val2['b']['name'];
                $data2[$val2['b']['name']]['b']['time'] = $val2['b']['time'];
                $data2[$val2['b']['name']]['b']['z_name'] = $val2['b']['z_name'];
                $data2[$val2['b']['name']]['b']['c_num1'] = $val2['b']['c_num1'];
                $data2[$val2['b']['name']]['b']['c_num2'] = $val2['b']['c_num2'];
                $data2[$val2['b']['name']]['b']['c_num3'] = $val2['b']['c_num3'];
                $data2[$val2['b']['name']]['b']['k_name'] = $val2['b']['k_name'];
                $data2[$val2['b']['name']]['b']['j_num1'] = $val2['b']['j_num1'];
                $data2[$val2['b']['name']]['b']['j_num2'] = $val2['b']['j_num2'];
                $data2[$val2['b']['name']]['b']['j_num3'] = $val2['b']['j_num3'];
                $data2[$val2['b']['name']]['w']['name'] = $val2['w']['name'];
                $data2[$val2['b']['name']]['w']['time'] = $val2['w']['time'];
                $data2[$val2['b']['name']]['w']['z_name'] = $val2['w']['z_name'];
                $data2[$val2['b']['name']]['w']['c_num1'] = $val2['w']['c_num1'];
                $data2[$val2['b']['name']]['w']['c_num2'] = $val2['w']['c_num2'];
                $data2[$val2['b']['name']]['w']['c_num3'] = $val2['w']['c_num3'];
                $data2[$val2['b']['name']]['w']['k_name'] = $val2['w']['k_name'];
                $data2[$val2['b']['name']]['w']['j_num1'] = $val2['w']['j_num1'];
                $data2[$val2['b']['name']]['w']['j_num2'] = $val2['w']['j_num2'];
                $data2[$val2['b']['name']]['w']['j_num3'] = $val2['w']['j_num3'];
                //$i++;
                $data2[$val2['b']['name']]['time2'] = $val2['b']['time'];
            }
        }
//        $last_names = array_column($data2,'time2');
//        array_multisort($last_names,SORT_DESC,$data2);
        return array_values($data2);
    }
}