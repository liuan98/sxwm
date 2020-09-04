<?php
namespace App\Service;


use App\Model\Bus;
use App\Model\Extend;
use App\Model\NewDay;
use App\Model\NewDayBus;
use App\Model\NewMonth;
use Hyperf\Cache\Cache;
use Hyperf\Utils\ApplicationContext;

class DdService
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
     * @param $data
     * @return array
     * todo:添加
     * 数据库数据
     */
    public function sqlData($data){
        $add = array();
        //钉钉参数 (必须参数）
        $add['userid'] = (isset($data['userid']) && !empty($data['userid']))?$data['userid']:'';
        $add['title'] = (isset($data['title']) && !empty($data['title']))?$data['title']:'客户';//职位
        $add['label_ids'] = isset($data['label_ids'])?implode(',',jsonToArray($data['label_ids'])):'';//标签列表
        $add['follower_user_id'] = (isset($data['follower_user_id']) && $data['follower_user_id'])?$data['follower_user_id']:'manager3528';//负责人userId
        $add['name'] = $data['name'];//名称
        $add['state_code'] = '86';//手机号国家码
        $add['createtime'] = time();
        $add['delmoney'] = isset($data['delmoney'])?floatval($data['delmoney']):'';
        $add['type'] = isset($data['type'])?$data['type']:4;
        $add['trade_id'] = isset($data['trade_id'])?$data['trade_id']:'';
        if($add['type']!=3) {
            $add['mobile'] = '13000000000';//手机号
            $add['remark'] = isset($data['mobile'])?$data['mobile']:'';//备注
        }else{
            $add['mobile'] = isset($data['mobile'])?$data['mobile']:'';//手机号
        }
        //(非必须参数)
        $add['share_dept_ids'] = (isset($data['share_dept_ids']))?implode(',',jsonToArray($data['share_dept_ids'])):'';//共享给的部门ID
        $add['address'] = isset($data['share_dept_ids'])?$data['share_dept_ids']:'';//地址
        $add['company_name'] = isset($data['company_name'])?$data['company_name']:'';//企业名
        $add['share_user_ids'] = (isset($data['share_user_ids']))?implode(',',jsonToArray($data['share_user_ids'])):'';//共享给的员工userId列表
        return $add;
    }

    /**
     * @param $data
     * @return array
     * todo:添加
     * 钉钉组装数据
     */
    public function ddData($data){
        $dadd = array();
        //钉钉参数 (必须参数）
        $dadd['title'] = (isset($data['title']) && !empty($data['title']))?$data['title']:'客户';//职位
        $dadd['label_ids'] = isset($data['label_ids'])?jsonToArray($data['label_ids']):[];//标签列表
        $dadd['follower_user_id'] = (isset($data['follower_user_id']) && $data['follower_user_id'])?$data['follower_user_id']:'manager3528';//负责人userId
        $dadd['name'] = $data['name'];//名称
        $dadd['state_code'] = '86';//手机号国家码
        $dadd['createtime'] = time();
        $dadd['mobile'] = '13000000000';
        if($data['type']!=3) {
            $dadd['remark'] = isset($data['mobile'])?$data['mobile']:'';//备注
        }else{
            $dadd['mobile'] = isset($data['mobile'])?$data['mobile']:'';//手机号
        }

        //(非必须参数)
        $dadd['share_dept_ids'] = (isset($data['share_dept_ids']))?jsonToArray($data['share_dept_ids']):[];//共享给的部门ID
        $dadd['address'] = isset($data['share_dept_ids'])?$data['share_dept_ids']:'';//地址
        $dadd['remark'] = isset($data['remark'])?$data['remark']:'';//备注
        $dadd['company_name'] = isset($data['company_name'])?$data['company_name']:'';//企业名
        $dadd['share_user_ids'] = (isset($data['share_user_ids']))?jsonToArray($data['share_user_ids']):[];//共享给的员工userId列表
        return $dadd;
    }

    /**
     * @param $data
     * @return array
     * todo:更新
     * 数据库数据
     */
    public function usqlData($data){
        $add = array();
        //钉钉参数 (必须参数）
        $add['title'] = (isset($data['title']) && !empty($data['title']))?$data['title']:'客户';//职位
        $add['label_ids'] = isset($data['label_ids'])?implode(',',jsonToArray($data['label_ids'])):'';//标签列表
        $add['follower_user_id'] = (isset($data['follower_user_id']) && $data['follower_user_id'])?$data['follower_user_id']:'manager3528';//负责人userId
        $add['name'] = $data['name'];//名称
        $add['delmoney'] = isset($data['delmoney'])?floatval($data['delmoney']):'';
        $add['type'] = isset($data['type'])?$data['type']:4;
        $add['trade_id'] = isset($data['trade_id'])?$data['trade_id']:'';
        if($add['type']!=3) {
            $add['remark'] = isset($data['mobile'])?$data['mobile']:'';//备注
        }else{
            $add['mobile'] = isset($data['mobile'])?$data['mobile']:'';//手机号
        }

        //(非必须参数)
        $add['share_dept_ids'] = (isset($data['share_dept_ids']))?implode(',',jsonToArray($data['share_dept_ids'])):'';//共享给的部门ID
        $add['address'] = isset($data['share_dept_ids'])?$data['share_dept_ids']:'';//地址
        $add['remark'] = isset($data['remark'])?$data['remark']:'';//备注
        $add['company_name'] = isset($data['company_name'])?$data['company_name']:'';//企业名
        $add['share_user_ids'] = (isset($data['share_user_ids']))?implode(',',jsonToArray($data['share_user_ids'])):'';//共享给的员工userId列表
        return $add;
    }

    /**
     * @param $data
     * @return array
     * todo:更新
     * 钉钉组装数据
     */
    public function uddData($data){
        $dadd = array();
        //钉钉参数 (必须参数）
        $dadd['user_id'] = $data['user_id'];//客户id
        $dadd['title'] = (isset($data['title']) && !empty($data['title']))?$data['title']:'客户';//职位
        $dadd['label_ids'] = isset($data['label_ids'])?jsonToArray($data['label_ids']):[];//标签列表
        $dadd['follower_user_id'] = (isset($data['follower_user_id']) && $data['follower_user_id'])?$data['follower_user_id']:'manager3528';//负责人userId
        $dadd['name'] = $data['name'];//名称
        if($data['type']!=3) {
            $dadd['remark'] = isset($data['mobile'])?$data['mobile']:'';//备注
        }

        //(非必须参数)
        $dadd['share_dept_ids'] = (isset($data['share_dept_ids']))?jsonToArray($data['share_dept_ids']):[];//共享给的部门ID
        $dadd['address'] = isset($data['share_dept_ids'])?$data['share_dept_ids']:'';//地址
        $dadd['remark'] = isset($data['remark'])?$data['remark']:'';//备注
        $dadd['company_name'] = isset($data['company_name'])?$data['company_name']:'';//企业名
        $dadd['share_user_ids'] = (isset($data['share_user_ids']))?jsonToArray($data['share_user_ids']):[];//共享给的员工userId列表
        return $dadd;
    }

    /**
     * @param $data
     * 递归
     */
    public function getDdList($page=0,$resd=array(),$mould){

        $res = self::Ddrequest($page,'','',$mould);
        if ($res['errcode'] != 0) {
            return fail($res['errmsg']);
        }

        $resds = array_merge($resd,$res['result']['data_list']);

        //要判断是不是有多页
        if($res['result']['has_more'] == true) {
            return self::getDdList($res['result']['next_cursor'],$resds,$mould);
        }

        return $resds;
    }

    /**
     * 请求钉钉访客日志
     */
    public function Ddrequest($page,$beginTime='',$endTime='',$mould='市场数据记录'){

        $redis = ApplicationContext::getContainer()->get(\Redis::class);
        $token = $redis->get('Dd_token');
        if(!$token) {
            $token = getAccessToken(config('ddLog.APP_KEY'), config('ddLog.APP_SECRET'));
            $redis->set('Dd_token', $token);
            $redis->expire('Dd_token', 3600);
        }

        $url = config('ddLog.OAPI_HOST') . '/topapi/report/list?access_token=' . $token;

        $y = date("Y");
        $m = date("m");
        $d = date("d");

        $beginTime = $beginTime?$beginTime:mktime(0, 0, 0, intval($m),intval($d),intval($y));//今天凌晨的时间戳
        $endTime = $endTime?$endTime:mktime(23, 59, 59, intval($m), intval($d),intval($y));//今天凌晨的时间戳

        $data['start_time'] = $beginTime.'000';//起始时间。时间的毫秒数
        $data['end_time'] = $endTime.'000';//截止时间。时间的毫秒数
        $data['template_name'] = $mould;//要查询的模板名称
        $data['userid'] = '';//员工的userid
        $data['cursor'] = $page;//查询游标，初始传入0，后续从上一次的返回值中获取
        $data['size'] = 20;//每页数据量, 最大值是20
        //$redis->del('Dd_token');
        $res = PostRequest($url, $data);
        return $res;
    }


    /**
     * 天统计数、
     * 新开+续费
     */
    public function  salesDay()
    {
        $dday = date('d');
        $redis = ApplicationContext::getContainer()->get(\Redis::class);
        $salesDay = $redis->get('salesDay');
        if (!$salesDay) {
            $data = array();
            for ($i = 1; $i <= (int)$dday; $i++) {
                if ($i < 10) {
                    $day = date('Y-m-0' . $i);
                } else {
                    $day = date('Y-m-' . $i);
                }
                $new = NewDay::getInstance()->countDayData(['day' => $day, 'type' => 1]);
                $renew = NewDay::getInstance()->countDayData(['day' => $day, 'type' => 2]);
                $data['new'][] = $new->money == 0 ? 0 : $new->money;
                $data['renew'][] = $renew->money == 0 ? 0 : $renew->money;
            }
            $salesDay = serialize($data);
            $redis->set('salesDay', $salesDay);
            $redis->expire('salesDay', 60);
        }
        return unserialize($salesDay);
    }
    /**
     * 月统计数
     * 新开/续费
     */
    public function  salesMonth(){
        $month = date('m');
        $info = array();
        //一年内统计
        for ($i=1; $i <= $month; $i++) {
            if($i<10) {
                $BeginDate = date('Y-0' . $i . '-01');
            }else{
                $BeginDate = date('Y-' . $i . '-01');
            }
            $endDate = strtotime("$BeginDate +1 month -1 day");
            $BeginDate = strtotime($BeginDate);
            $total = NewMonth::getInstance()->countData($BeginDate,$endDate);
            $info[]=$total->num==0?0:$total->num;
        }
        $gMonth = ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'];
        $data['data'] = $info;
        $data['mouth'] = $gMonth;
        return $data;
    }

    /**
     * 月统计数
     * 市场
     */
    public function  newDay()
    {
        //$day = oneDay();
        $month = date('m');
        //一年内统计
        if ((int)$month < 10) {
            $BeginDate = date('Y-0' . $month . '-01');
        } else {
            $BeginDate = date('Y-' . $month . '-01');
        }
        $endDate = strtotime("$BeginDate +1 month -1 day");
        $BeginDate = strtotime($BeginDate);

        $data3 = NewDay::getInstance()->getData2(['dep_type' => 1],$BeginDate,$endDate);

        $data11 = array();
        $data22 = array();
        $data33 = array();
        $data44 = array();
        if ($data3) {
            foreach ($data3 as $vlu1) {
                $find1 = NewDay::getInstance()->findData2(['userid' => $vlu1->userid, 'type' => 1, 'dep_type' => 1], $BeginDate, $endDate);
                if (isset($find1->userid)) {
                    $data11['data'][] = $find1->money;
                    $data11['name'][] = $find1->user_name;
                } else {
                    $data11['data'][] = 0;
                    $data11['name'][] = $vlu1->user_name;
                }
                $find2 = NewDay::getInstance()->findData2(['userid' => $vlu1->userid, 'type' => 2, 'dep_type' => 1], $BeginDate, $endDate);
                if (isset($find2->userid)) {
                    $data22['data'][] = $find2->money;
                    $data22['name'][] = $find2->user_name;
                } else {
                    $data22['data'][] = 0;
                    $data22['name'][] = $vlu1->user_name;
                }
                $data33['data'][] = $vlu1->money;
                $data33['name'][] = $vlu1->user_name;
                $find3 = NewDay::getInstance()->findData3(['userid' => $vlu1->userid, 'type' => 1, 'dep_type' => 1], $BeginDate, $endDate);
                if (count($find3) > 0) {
                    $reportSum = 0;
                    foreach ($find3 as $fd) {
                        $reportSum += count(explode(',', $fd->report_id));
                    }
                    $data44['data'][] = $reportSum;
                    $data44['name'][] = $vlu1->user_name;
                } else {
                    $data44['data'][] = 0;
                    $data44['name'][] = $vlu1->user_name;
                }
            }
        }
        $data['data1'] = $data11;//新开
        $data['data2'] = $data22;//续费
        $data['data3'] = $data33;//总业绩
        $data['data4'] = $data44;//新开数
        return $data;
    }

    /**
     * 月统计数
     * 运营
     */
    public function  renewDay(){
        //$day = oneDay();
        $month = date('m');
        //一年内统计
        if ((int)$month < 10) {
            $BeginDate = date('Y-0' . $month . '-01');
        } else {
            $BeginDate = date('Y-' . $month . '-01');
        }
        $endDate = strtotime("$BeginDate +1 month -1 day");
        $BeginDate = strtotime($BeginDate);

        $data3 = NewDay::getInstance()->getData2(['dep_type'=>2],$BeginDate,$endDate);
        $data11 = array();
        $data22 = array();
        $data33 = array();
        $data44 = array();
        $tol_renew = 0;
        if($data3) {
            foreach ($data3 as $vlu2) {
                $find3 = NewDay::getInstance()->findData3(['userid' => $vlu2->userid,'type' => 2,'dep_type' => 2],$BeginDate,$endDate);
                if (count($find3) > 0) {
                    foreach ($find3 as $fd3) {
                        $tol_renew += count(explode(',', $fd3->report_id));
                    }
                }
            }
            foreach ($data3 as $vlu1) {
                $find1 = NewDay::getInstance()->findData2(['userid' => $vlu1->userid,'type' => 1,'dep_type' => 2],$BeginDate,$endDate);
                if (isset($find1->userid)) {
                    $data11['data'][] = $find1->money;
                    $data11['name'][] = $find1->user_name;
                } else {
                    $data11['data'][] = 0;
                    $data11['name'][] = $vlu1->user_name;
                }
                $find2 = NewDay::getInstance()->findData2(['userid' => $vlu1->userid,'type' => 2,'dep_type' => 2],$BeginDate,$endDate);
                if (isset($find2->userid)) {
                    $data22['data'][] = $find2->money;
                    $data22['name'][] = $find2->user_name;
                } else {
                    $data22['data'][] = 0;
                    $data22['name'][] = $vlu1->user_name;
                }
                $data33['data'][] = $vlu1->money;
                $data33['name'][] = $vlu1->user_name;

                $find4 = NewDay::getInstance()->findData3(['userid' => $vlu1->userid, 'type' => 2, 'dep_type' => 2], $BeginDate, $endDate);
                if (count($find4) > 0 && $tol_renew > 0) {
                    $reportSum = 0;
                    foreach ($find4 as $fd) {
                        $reportSum += count(explode(',', $fd->report_id));
                    }
                    $data44['data'][] = round((($reportSum / $tol_renew) * 100), 2);
                    $data44['name'][] = $vlu1->user_name;
                } else {
                    $data44['data'][] = 0;
                    $data44['name'][] = $vlu1->user_name;
                }
            }
        }
        $data['data1'] = $data11;//新开
        $data['data2'] = $data22;//续费
        $data['data3'] = $data33;//总业绩
        $data['data4'] = $data44;//续费率
        return $data;
    }


    /**
     * 周统计数
     * 推广/SEO/竞价曲线图
     */
    public function  extendDay(){
        $x_name = [];
        $y_data = [];
        foreach (get_week_arr() as $v){
            $new = Extend::getInstance()->findData(['day' => $v['week_date']]);
            $x_name[] = isset($new->seo_num)?$new->seo_num:0;
            $y_data[] = isset($new->bidding_num)?$new->bidding_num:0;
        }
        $data['data1'] = $x_name;
        $data['data2'] = $y_data;
        return $data;
    }


    /**
     * 周统计数
     * 推广/SEO/竞价横向图
     */
    public function  extendWeek(){
        $x_name = [];
        $y_data = [];
        $y_data2 = [];
        foreach (get_week_arr() as $v){
            $new = Extend::getInstance()->findData(['day' => $v['week_date']]);
            $x_name[] = isset($new->money)?$new->money:0;
            $tol_seo = isset($new->seo_num)?$new->seo_num:0;
            $tol_bidding = isset($new->bidding_num)?$new->bidding_num:0;
            $y_data[] = $tol_seo+$tol_bidding;
            $y_data2[] = isset($new->tol_money)?$new->tol_money:0;
        }
        $data['data1'] = $x_name;
        $data['data2'] = $y_data;
        $data['data3'] = $y_data2;
        return $data;
    }

    /**
     * 本月销售数据/目标
     */
    public function monthData()
    {
        $month = date('m');
        $info = array();
        //一年内统计
        $BeginDate = date('Y-' . $month . '-01');

        $endDate = strtotime("$BeginDate +1 month -1 day");
        $BeginDate = strtotime($BeginDate);

        //本月总加人数
        $total1 = Extend::getInstance()->countData($BeginDate, $endDate);
        //本月总消耗成本
        $total1_1 = Extend::getInstance()->countDayData_2($BeginDate, $endDate);
        //市场本月业绩
        $total2 = NewDay::getInstance()->countData($BeginDate, $endDate);

        //运营本月业绩
        $total3 = NewDayBus::getInstance()->countData($BeginDate, $endDate);

        $info['data1'] = [$total1->num == 0 ? 0 : $total1->num,$total2->money == 0 ? 0 : $total2->money,$total3->money == 0 ? 0 : $total3->money];
        $info['data2'] = [$total1_1->money == 0 ? 0 : $total1_1->money,'700000'];
        return $info;
    }

    /**
     * 推广数据
     * 竞价
     */
    public function extend1(){
        $info = array();
        $info['data1'] = array();
        $info['data2'] = array();
        $info['data3'] = array();
        $week = get_week_arr();
        foreach ($week as $val) {
            $findData = Extend::getInstance()->findData(['day'=>$val['week_date']]);
            //竞价
            $info['data1'][] = (isset($findData->bidding_num) && $findData->bidding_num > 0)?$findData->bidding_num:0;
            //竞价成本
            $info['data2'][] = (isset($findData->money) && $findData->money > 0)?$findData->money:0;
            //竞价转化个数
            $info['data3'][] = (isset($findData->bidding_trans) && $findData->bidding_trans > 0)?$findData->bidding_trans:0;
        }
        return $info;
    }
    /**
     * 推广数据
     * 竞价主攻非主攻
     */
    public function extend2(){
        $info = array();
        $info['data1'] = array();
        $info['data2'] = array();
        $info['data3'] = array();
        $week = get_week_arr();
        foreach ($week as $val) {
            $findData = Extend::getInstance()->findData(['day'=>$val['week_date']]);
            //主攻
            $info['data1'][] = (isset($findData->bidding_trade) && $findData->bidding_trade > 0)?$findData->bidding_trade:0;
            //非主攻
            $info['data2'][] = (isset($findData->bidding_notrade) && $findData->bidding_notrade > 0)?$findData->bidding_notrade:0;
            //总加人数
            $info['data3'][] = (isset($findData->bidding_trade) && isset($findData->bidding_notrade) && ($findData->bidding_trade + $findData->bidding_notrade) > 0)?$findData->bidding_trade + $findData->bidding_notrade:0;
        }
        return $info;
    }
    /**
     * 推广数据
     * SEO
     */
    public function extend3(){
        $info = array();
        $info['data1'] = array();
        $info['data2'] = array();
        $week = get_week_arr();
        foreach ($week as $val) {
            $findData = Extend::getInstance()->findData(['day'=>$val['week_date']]);
            //SEO
            $info['data1'][] = (isset($findData->seo_num) && $findData->seo_num > 0)?$findData->seo_num:0;
            //SEO个数
            $info['data2'][] = (isset($findData->bidding_num) && $findData->bidding_num > 0)?$findData->bidding_num:0;
        }
        return $info;
    }
    /**
     * 推广数据
     * SEO主攻非主攻
     */
    public function extend4(){
        $info = array();
        $info['data1'] = array();
        $info['data2'] = array();
        $info['data3'] = array();
        $week = get_week_arr();
        foreach ($week as $val) {
            $findData = Extend::getInstance()->findData(['day'=>$val['week_date']]);
            //主攻
            $info['data1'][] = (isset($findData->seo_trade) && $findData->seo_trade > 0)?$findData->seo_trade:0;
            //非主攻
            $info['data2'][] = (isset($findData->seo_notrade) && $findData->seo_notrade > 0)?$findData->seo_notrade:0;
            //总加人数
            $info['data3'][] = (isset($findData->seo_trade) && isset($findData->seo_notrade) && ($findData->seo_trade + $findData->seo_notrade) > 0)?$findData->seo_trade + $findData->seo_notrade:0;
        }
        return $info;
    }

    /**
     * 市场数据
     * 个人业绩表
     */
    public function market1(){
        $info = array();
        $info['data1'] = array();
        $info['data2'] = array();
        $info['data3'] = array();
        $info['data4'] = array();
        $day = oneDay();
        $findData = NewDay::getInstance()->getNameData(['day'=>$day]);
        foreach ($findData as $val) {
            $findData2 = NewDay::getInstance()->countDayData(['day'=>$day,'type'=>1,'userid'=>$val['userid']]);
            $findData3 = NewDay::getInstance()->countDayData(['day'=>$day,'type'=>2,'userid'=>$val['userid']]);
            //新开业绩
            $info['data1'][] = (isset($findData2->money) && $findData2->money > 0)?$findData2->money:0;
            //续费业绩
            $info['data2'][] = (isset($findData3->money) && $findData3->money > 0)?$findData3->money:0;
            //总业绩
            $info['data3'][] = (isset($val->money) && $val->money > 0)?$val->money:0;
            //姓名
            $info['data4'][] = isset($val->user_name)?$val->user_name:'无名';
        }
        return $info;
    }
    /**
     * 市场数据
     * 新开资源
     */
    public function market2(){
        $info = array();
        $info['data1'] = array();
        $info['data2'] = array();
        $week = get_week_arr();
        foreach ($week as $val) {
            $findData1 = NewDay::getInstance()->countDayData_1(['day'=>$val['week_date']]);
            $findData2 = NewDay::getInstance()->countDayData_1(['day'=>$val['week_date'],'source'=>1]);
            //新开单数
            $info['data1'][] = (isset($findData1->num) && $findData1->num > 0)?$findData1->num:0;
            //自找资源
            $info['data2'][] = (isset($findData2->num) && $findData2->num > 0)?$findData2->num:0;
        }
        return $info;
    }
    /**
     * 市场数据
     * 竞价主攻非主攻
     */
    public function market3(){
        $info = array();
        $info['data1'] = array();
        $info['data2'] = array();
        $info['data3'] = array();
        $info['data4'] = array();
        $day = oneDay();
        $findData = NewDay::getInstance()->getNameData(['day'=>$day]);
        foreach ($findData as $val) {
            $findData2 = NewDay::getInstance()->countDayData(['day'=>$day,'source'=>2,'userid'=>$val['userid']]);
            $findData3 = NewDay::getInstance()->countDayData(['day'=>$day,'trade'=>1,'userid'=>$val['userid']]);
            $findData4 = NewDay::getInstance()->countDayData(['day'=>$day,'trade'=>2,'userid'=>$val['userid']]);
            //竞价
            $info['data1'][] = (isset($findData2->money) && $findData2->money > 0)?$findData2->money:0;
            //主攻
            $info['data2'][] = (isset($findData3->money) && $findData3->money > 0)?$findData3->money:0;
            //非主攻
            $info['data3'][] = (isset($findData4->money) && $findData4->money > 0)?$findData4->money:0;
            //姓名
            $info['data4'][] = isset($val->user_name)?$val->user_name:'无名';
        }
        return $info;
    }

    /**
     * 市场数据
     * 竞价自找开单率
     * (按月统计)
     */
    public function market4(){
        $info = array();
        $info['data1'] = array();
        $info['data2'] = array();
        $info['data3'] = array();
        $month = date('m');
        //一年内统计
        $BeginDate = date('Y-' . $month . '-01');
        $endDate = strtotime("$BeginDate +1 month -1 day");
        $BeginDate = strtotime($BeginDate);
        $findData = NewDay::getInstance()->getData2(['type'=>1],$BeginDate,$endDate);
        foreach ($findData as $val) {
            $findData1 = NewDay::getInstance()->countDayData_2(['source'=>2,'type'=>1,'userid'=>$val['userid']],$BeginDate,$endDate);
            $findData2 = NewDay::getInstance()->countDayData_2(['source'=>1,'type'=>1,'userid'=>$val['userid']],$BeginDate,$endDate);
            //竞价开单率
            $info['data1'][] = (isset($findData1->money) && isset($val->money)) ? round((($findData1->money / $val->money) * 100), 2):0;
            //自找开单率
            $info['data2'][] = (isset($findData2->money) && isset($val->money)) ? round((($findData2->money / $val->money) * 100), 2):0;
            //姓名
            $info['data3'][] = isset($val->user_name)?$val->user_name:'无名';
        }
        return $info;
    }

    /**
     * 运营数据
     * 个人业绩表
     * （按月计算）
     */
    public function operative1(){
        $info = array();
        $info['data1'] = array();
        $info['data2'] = array();
        $info['data3'] = array();
        $info['data4'] = array();
        $month = date('m');
        //一年内统计
        $BeginDate = date('Y-' . $month . '-01');
        $endDate = strtotime("$BeginDate +1 month -1 day");
        $BeginDate = strtotime($BeginDate);
        $findData = NewDayBus::getInstance()->getData2(['type'=>2],$BeginDate,$endDate);
        foreach ($findData as $val) {
            $findData1 = NewDayBus::getInstance()->countDayData_1(['type'=>2,'userid'=>$val['userid']],$BeginDate,$endDate);
            $findData2 = NewDayBus::getInstance()->countDayData_1(['trade'=>1,'type'=>2,'userid'=>$val['userid']],$BeginDate,$endDate);
            $findData3 = NewDayBus::getInstance()->countDayData_1(['trade'=>2,'type'=>2,'userid'=>$val['userid']],$BeginDate,$endDate);
            //续费率
            $info['data1'][] = (isset($findData1->money) && isset($val->money)) ? round((($findData1->money / $val->money) * 100), 2):0;
            //主攻续费率
            $info['data2'][] = (isset($findData2->money) && isset($val->money)) ? round((($findData2->money / $val->money) * 100), 2):0;
            //非主攻续费率
            $info['data3'][] = (isset($findData3->money) && isset($val->money)) ? round((($findData3->money / $val->money) * 100), 2):0;
            //姓名
            $info['data4'][] = isset($val->user_name)?$val->user_name:'无名';
        }
        return $info;
    }
    /**
     * 运营数据
     * 介绍数据
     *(按月统计)
     */
    public function operative2(){
        $info = array();
        $info['data1'] = array();
        $info['data2'] = array();
        $info['data3'] = array();
        $day = oneDay();
        $month = date('m');
        //一年内统计
        $BeginDate = date('Y-' . $month . '-01');

        $endDate = strtotime("$BeginDate +1 month -1 day");
        $BeginDate = strtotime($BeginDate);

        $findData = Bus::getInstance()->getData2($BeginDate,$endDate);
        foreach ($findData as $val) {
            //消耗金额
            $info['data1'][] = (isset($val->money) && $val->money > 0)?$val->money:0;
            //转介绍
            $info['data2'][] = (isset($val->num) && $val->num > 0)?$val->num:0;
            //姓名
            $info['data3'][] = isset($val->user_name)?$val->user_name:'无名';
        }
        return $info;
    }
    /**
     * 运营数据
     * 竞价主攻非主攻
     */
    public function operative3(){
        $info = array();
        $info['data1'] = array();
        $info['data2'] = array();
        $info['data3'] = array();
        $info['data4'] = array();
        $day = oneDay();
        $findData = NewDayBus::getInstance()->getNameData(['day'=>$day]);
        foreach ($findData as $val) {
            $findData2 = NewDayBus::getInstance()->countDayData(['day'=>$day,'type'=>1,'userid'=>$val['userid']]);
            $findData3 = NewDayBus::getInstance()->countDayData(['day'=>$day,'type'=>2,'userid'=>$val['userid']]);
            //新开
            $info['data2'][] = (isset($findData2->money) && $findData2->money > 0)?$findData2->money:0;
            //续费
            $info['data3'][] = (isset($findData3->money) && $findData3->money > 0)?$findData3->money:0;
            //业绩
            $info['data1'][] = (isset($val->money) && $val->money > 0)?$val->money:0;
            //姓名
            $info['data4'][] = isset($val->user_name)?$val->user_name:'无名';
        }
        return $info;
    }
    /**
     * 运营数据
     * 主攻非主攻业绩
     * （按天统计）
     */
    public function operative4(){
        $info = array();
        $week = get_week_arr();
        $info['data1'] = array();
        $info['data2'] = array();
        foreach ($week as $val) {
            $findData1 = NewDayBus::getInstance()->countDayData(['day'=>$val['week_date'],'trade'=>1,'type'=>2]);
            $findData2 = NewDayBus::getInstance()->countDayData(['day'=>$val['week_date'],'trade'=>2,'type'=>2]);
            //主攻续费
            $info['data1'][] = (isset($findData1->money) && $findData1->money > 0)?$findData1->money:0;
            //非主攻续费
            $info['data2'][] = (isset($findData2->money) && $findData2->money > 0)?$findData2->money:0;
        }

        return $info;
    }

}