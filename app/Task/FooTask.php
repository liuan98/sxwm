<?php
declare(strict_types=1);
namespace App\Task;

use App\AsyncTask\BusCronJob;
use App\AsyncTask\BusSocketJob;
use App\AsyncTask\CronJob;
use App\AsyncTask\ExdCronJob;
use App\AsyncTask\SocketJob;
use App\Model\NewDay;
use App\Model\NewMonth;
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\Utils\ApplicationContext;

class FooTask
{
    private $driver;
    public function __construct()
    {
        $this->driver = ApplicationContext::getContainer()->get(DriverFactory::class)->get('default');
    }
//    /**
//     * 每天统计市场数据
//     */
//    public function execute()
//    {
//        return $this->driver->push(new CronJob('CronJob'), 0);
//    }
    /**
     * 每天统计运营介绍数据
     */
    public function BusExecute()
    {
        return $this->driver->push(new BusCronJob('BusCronJob'), 0);
    }
    /**
     * 每天统计推广数据
     */
    public function ExdExecute()
    {
        return $this->driver->push(new ExdCronJob('ExdCronJob'), 0);
    }

    /**
     * 每10分钟更新一次月统计
     */
    public function updateMonthCount()
    {
        $BeginDate = date('Y-m-01', time());
        $endDate = strtotime("$BeginDate +1 month -1 day");
        $BeginDate2 = strtotime($BeginDate);
        $total = NewDay::getInstance()->countData($BeginDate2, $endDate);

        $add['money'] = $total->money ? $total->money : 0;
        $add['updatetime'] = time();

        $find = NewMonth::getInstance()->findData(['day' =>$BeginDate]);
        if($find) {
            NewMonth::getInstance()->editData(['id' => $find->id], $add);
        }else{
            $add['day'] = date('Y-m-01', time());
            $add['addtime'] = strtotime(date('Y-m-08', time()));
            NewMonth::getInstance()->addData($add);
        }
    }



    /**
     * 每月统计
     */
    public function monthCount()
    {
        $BeginDate = date('Y-m-01', strtotime('-1 month'));
        $endDate = strtotime("$BeginDate +1 month -1 day");
        $BeginDate = strtotime($BeginDate);
        $total = NewDay::getInstance()->countData($BeginDate, $endDate);

        $add['money'] = $total->money ? $total->money : 0;
        $day = date('Y-m-01', strtotime('-1 month'));
        $add['updatetime'] = time();

        $find = NewMonth::getInstance()->findData(['day' =>$day]);
        if($find) {
            NewMonth::getInstance()->editData(['id' => $find->id], $add);
        }else{
            $add['day'] = date('Y-m-01', strtotime('-1 month'));
            $add['addtime'] = strtotime(date('Y-m-08', strtotime('-1 month')));
            NewMonth::getInstance()->addData($add);
        }
    }

    /**
     * @return bool
     * 市场模板
     */
    public function sleepAction()
    {
        return $this->driver->push(new SocketJob('SocketJob'), 0);
    }
    /**
     * @return bool
     * 运营模板
     */
    public function BusSleepAction()
    {
        return $this->driver->push(new BusSocketJob('BusSocketJob'), 0);
    }
}