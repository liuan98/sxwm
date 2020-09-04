<?php

declare(strict_types=1);

namespace App\AsyncTask;

use App\Model\NewDay;
use App\Model\Sales;
use App\Service\DdService;
use Hyperf\AsyncQueue\Job;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;
use Hyperf\WebSocketClient\ClientFactory;

/**
 * Class SocketJob
 * @package App\AsyncTask
 * 市场异步数据循环钉钉日志
 */
class SocketJob extends Job
{
    public $params;
    public function __construct($params)
    {
        // 这里最好是普通数据，不要使用携带 IO 的对象，比如 PDO 对象
        $this->params = $params;
    }

    public function handle()
    {
        $client = ApplicationContext::getContainer()->get(ClientFactory::class)->create('127.0.0.1:9502');
        $y = date("Y");
        $m = date("m");
        $d = date("d");
        $H = date("H");
        $i = date("i");
        $s = date("s");
        $beg = date('s',(time()-10));
        $beginTime = mktime(intval($H), intval($i), intval($beg), intval($m), intval($d), intval($y));//今天凌晨的时间戳
        $endTime = mktime(intval($H), intval($i), intval($s), intval($m), intval($d), intval($y));//今天凌晨的时间戳

        $info = DdService::getInstance()->Ddrequest(0,$beginTime,$endTime);
        if (isset($info['result']['data_list']) && !empty($info['result']['data_list'])) {
            $data_list = $info['result']['data_list'];
            foreach ($data_list as $vv) {
                if (isset($vv['report_id']) && !empty($vv['report_id'])) {
                    $findSale = Sales::getInstance()->findData(['report_id' => $vv['report_id']]);
                    if (!$findSale) {

                        //记录数据
                        $findNewData = NewDay::getInstance()->findData(['report_id' => $vv['report_id']]);
                        if(!$findNewData) {
                            $data = array();
                            $day = oneDay();
                            $data['userid'] = $vv['creator_id'];
                            $data['user_name'] = $vv['creator_name'];
                            $data['money'] = $vv['contents'][5]['value'];
                            $data['day'] = $day;
                            $data['type'] = (isset($vv['contents'][2]['value']) && $vv['contents'][2]['value']=='新开')?1:2;
                            $data['trade'] = (isset($vv['contents'][3]['value']) && $vv['contents'][3]['value']=='主攻')?1:2;
                            $data['source'] = (isset($vv['contents'][4]['value']) && $vv['contents'][4]['value']=='自找资源')?1:2;
                            $data['report_id'] = $vv['report_id'];
                            $data['updatetime'] = time();
                            $data['addtime'] = time();
                            if (isset($vv['report_id']) && !empty($vv['report_id'])) NewDay::getInstance()->addData($data);
                        }

                        //日志数据
                        $saleData = array();
                        $saleData['contents'] = serialize($vv['contents']);
                        $saleData['create_time'] = $vv['create_time'];
                        $saleData['creator_id'] = $vv['creator_id'];
                        $saleData['creator_name'] = $vv['creator_name'];
                        $saleData['dept_name'] = $vv['dept_name'];
                        $saleData['report_id'] = $vv['report_id'];
                        $saleData['images'] = serialize($vv['images']);
                        $saleData['remark'] = $vv['remark'];
                        $saleData['template_name'] = $vv['template_name'];
                        $saleData['addtime'] = time();
                        $addSale = Sales::getInstance()->addData($saleData);
                        if ($addSale) {
                            $meg = '恭喜市场部' . $vv['creator_name'] . $vv['contents'][2]['value'] . $vv['contents'][5]['value'] . '元';
                            $client->push($meg);
                           // sleep(1);
                        }
                    }
                }
            }
        }
        make(LoggerFactory::class)->make()->info($this->params);
    }
}