<?php
/**
 * Created by PhpStorm.
 * User: liuan
 * Date: 2020/7/21
 * Time: 9:22
 */
declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Db;
use Hyperf\DbConnection\Model\Model;
/**
 */
class Confirm extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'home_pay';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * @var bool
     * 不允许添加时间戳
     */
    public $timestamps = false;

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

    public function Order($uid,$where){
        $list = $this->where($where)
            ->where('uid',$uid)
            ->select('order_number')
            ->orderBy('id','desc')
            ->get();

        foreach ($list as $k => &$v){
            $v['list'] = Confirm::query()
                ->from('home_pay as p')
                ->leftJoin('home_order as o','p.order_number','=','o.order')
                ->leftJoin('admin_commodity as c','o.goods_id','=','c.id')
                ->where('o.order',$v['order_number'])
                ->select('p.id','o.order','c.name','c.img','c.money','c.standard','o.number','p.status')
                ->get();
        }

        return $list;
    }


    public function getOrder($uid,$status,$where){
        $list = $this->where($where)
            ->where('uid',$uid)
            ->where('status',$status)
            ->select('order_number')
            ->orderBy('id','desc')
            ->get();

        foreach ($list as $k => &$v){
            $v['list'] = Confirm::query()
                ->from('home_pay as p')
                ->leftJoin('home_order as o','p.order_number','=','o.order')
                ->leftJoin('admin_commodity as c','o.goods_id','=','c.id')
                ->where('o.order',$v['order_number'])
                ->select('p.id','o.order','c.name','c.img','c.money','c.standard','o.number','p.status')
                ->get();
        }

        return $list;
    }


    public function getStay($where,$uid){
        $list = $this->where($where)
            ->where('uid',$uid)
            ->where('status',5)
            ->where('evaluate',1)
            ->select('order_number')
            ->orderBy('id','desc')
            ->get();

        foreach ($list as $k => &$v){
            $v['list'] = Confirm::query()
                ->from('home_pay as p')
                ->leftJoin('home_order as o','p.order_number','=','o.order')
                ->leftJoin('admin_commodity as c','o.goods_id','=','c.id')
                ->where('o.order',$v['order_number'])
                ->select('p.id','o.order','c.name','c.img','c.money','c.money','c.standard','o.number','p.evaluate')
                ->get();
        }

        return $list;

    }

    public function today(){
        $result['start_time'] = strtotime(date('Y-m-d 00:00:00', time()));
        $result['end_time'] = strtotime(date('Y-m-d 23:59:59', time()));

        $data = $this->whereBetween('add_time', [$result['start_time'], $result['end_time']])
            ->count();

        return $data;
    }


    public function orderPage(){
        $result['start_time'] = strtotime(date('Y-m-d 00:00:00', time()));
        $result['end_time'] = strtotime(date('Y-m-d 23:59:59', time()));

        $data['send'] = $this->whereBetween('add_time', [$result['start_time'], $result['end_time']])
            ->where('status',3)
            ->count();//待发货

        $data['unpaid'] = $this->whereBetween('add_time', [$result['start_time'], $result['end_time']])
            ->where('status',2)
            ->count();//待结算

        $data['make'] = $this->whereBetween('add_time', [$result['start_time'], $result['end_time']])
            ->where('status',5)
            ->count();//已成交

        $data['defeated'] = $this->whereBetween('add_time', [$result['start_time'], $result['end_time']])
            ->where('status',7)
            ->count();//交易失败
        return $data;
    }


    public function month($id){
        if(!empty($id)){
            $last['first'] = strtotime(date('Y-m-01',time()));//获取指定月份的第一天
            $last['end'] = strtotime(date('Y-m-t',time())); //获取指定月份的最后一天
            $data = $this->whereBetween('add_time', [$last['first'], $last['end']])
                ->where('warehouse_id',$id)
                ->sum('price');

            return $data;
        }
        $last['first'] = strtotime(date('Y-m-01',time()));//获取指定月份的第一天
        $last['end'] = strtotime(date('Y-m-t',time())); //获取指定月份的最后一天
        $data = $this->whereBetween('add_time', [$last['first'], $last['end']])
            ->sum('price');

        return $data;
    }

    public function day($id){
        $result['start_time'] = strtotime(date('Y-m-d 00:00:00', time()));
        $result['end_time'] = strtotime(date('Y-m-d 23:59:59', time()));

        if(!empty($id)){
            $data = $this->whereBetween('add_time', [$result['start_time'], $result['end_time']])
                ->where('warehouse_id',$id)
                ->sum('price');

            return $data;
        }
        $data = $this->whereBetween('add_time', [$result['start_time'], $result['end_time']])
            ->sum('price');

        return $data;
    }

    public function monthTime($id){
        $last['first'] = strtotime(date('Y-m-01',time()));//获取指定月份的第一天
        $last['end'] = strtotime(date('Y-m-t',time())); //获取指定月份的最后一天

        if(!empty($id)){
            $data = $this->whereBetween('add_time', [$last['first'], $last['end']])
                ->where('status','>',1)
                ->where('warehouse_id',$id)
                ->count();

            return $data;
        }
        $data = $this->whereBetween('add_time', [$last['first'], $last['end']])
            ->where('status','>',1)
            ->count();

        return $data;
    }

    public function dayTime($id){
        $result['start_time'] = strtotime(date('Y-m-d 00:00:00', time()));
        $result['end_time'] = strtotime(date('Y-m-d 23:59:59', time()));

        if(!empty($id)){
            $data = $this->whereBetween('add_time', [$result['start_time'], $result['end_time']])
                ->where('status','>',1)
                ->where('warehouse_id',$id)
                ->count();

            return $data;
        }
        $data = $this->whereBetween('add_time', [$result['start_time'], $result['end_time']])
            ->where('status','>',1)
            ->count();

        return $data;
    }

    public function record($last,$id){
        if(!empty($id)){
            foreach ($last as $k => &$v){
                $v['sum'] = $this->whereBetween('add_time', [$v['first'], $v['end']])
                    ->count();//所有
                $v['finish'] = $this->whereBetween('add_time', [$v['first'], $v['end']])
                    ->where('status',5)
                    ->count();//已完成
                $v['due'] = $this->whereBetween('add_time', [$v['first'], $v['end']])
                    ->where('status',3)
                    ->count();//代发货
                $v['cancel'] = $this->whereBetween('add_time', [$v['first'], $v['end']])
                    ->where('status',7)
                    ->count();//已取消
            }


            return $last;
        }
        foreach ($last as $k => &$v){
            $v['sum'] = $this->whereBetween('add_time', [$v['first'], $v['end']])
                ->count();//所有
            $v['finish'] = $this->whereBetween('add_time', [$v['first'], $v['end']])
                ->where('status',5)
                ->count();//已完成
            $v['due'] = $this->whereBetween('add_time', [$v['first'], $v['end']])
                ->where('status',3)
                ->count();//代发货
            $v['cancel'] = $this->whereBetween('add_time', [$v['first'], $v['end']])
                ->where('status',7)
                ->count();//已取消
        }


        return $last;
    }


    public function getAll($where,$status,$time){
        if(empty($time)){
            $result['start_time'] = strtotime(date('Y-m-d 00:00:00', 0));
            $result['end_time'] = strtotime(date('Y-m-d 23:59:59', time()));
        }else{
            $result['start_time'] = strtotime(date('Y-m-d 00:00:00', $time));
            $result['end_time'] = strtotime(date('Y-m-d 23:59:59', $time));
        }

        if(!empty($status)){
            $data = Confirm::query()
                ->from('home_pay as p')
                ->leftJoin('home_order as o','p.order_number','=','o.order')
                ->leftJoin('home_site as s','p.site_id','=','s.id')
                ->where($where)
                ->where('p.status',$status)
                ->whereBetween('p.add_time', [$result['start_time'], $result['end_time']])
                ->select('p.id','p.order_number','s.name','s.phone','p.money','p.payment','p.start','p.end','p.add_time','p.status',DB::raw('sum(o.number) as number'))
                ->orderBy('p.id','desc')
                ->groupBy('o.order')
                ->take(20)
                ->get();

            return $data;
        }

        $data = Confirm::query()
            ->from('home_pay as p')
            ->leftJoin('home_order as o','p.order_number','=','o.order')
            ->leftJoin('home_site as s','p.site_id','=','s.id')
            ->where($where)
            ->whereBetween('p.add_time', [$result['start_time'], $result['end_time']])
            ->select('p.id','p.order_number','s.name','s.phone','p.money','p.payment','p.start','p.end','p.add_time','p.status',DB::raw('sum(o.number) as number'))
            ->orderBy('p.id','desc')
            ->groupBy('o.order')
            ->take(20)
            ->get();

        return $data;

    }

    public function orderDetails($id){
        $data = objectToArray(Confirm::query()
            ->from('home_pay as p')
            ->leftJoin('home_order as o','p.order_number','=','o.order')
            ->leftJoin('home_site as s','p.site_id','=','s.id')
            ->where('p.id',$id)
            ->select('p.id','p.order_number','s.name','s.phone','s.site','s.detail','p.payment','p.start','p.end','p.status','p.add_time','p.pay_time','p.deliver_time','p.complete_time','p.remark')
            ->first());

        return $data;
    }

    public function transaction(){
        $result['start_time'] = strtotime(date('Y-m-01'));//获取月初时间
        $result['end_time'] = strtotime(date('Y-m-t'));//获取月末时间

        $data = $this->where('status','>',2)
            ->where('status','!=',7)
            ->whereBetween('pay_time', [$result['start_time'], $result['end_time']])
            ->sum('price');

        return $data;
    }

    public function getDay(){
        $result['start_time'] = strtotime(date('Y-m-d 00:00:00', time()));
        $result['end_time'] = strtotime(date('Y-m-d 23:59:59', time()));

        $data = $this->where('status','>',2)
            ->where('status','!=',7)
            ->whereBetween('pay_time', [$result['start_time'], $result['end_time']])
            ->sum('price');

        return $data;
    }

    public function getDeal($time,$where){
        if(empty($time)){
            $result['start_time'] = strtotime(date('Y-m-d 00:00:00', 0));
            $result['end_time'] = strtotime(date('Y-m-d 23:59:59', time()));
        }else{
            $result['start_time'] = strtotime(date('Y-m-d 00:00:00', $time));
            $result['end_time'] = strtotime(date('Y-m-d 23:59:59', $time));
        }
        $data = $this->select('id','order_number','money','payment','pay_time')
            ->whereBetween('pay_time', [$result['start_time'], $result['end_time']])
            ->where($where)
            ->orderBy('id','desc')
            ->take(20)
            ->get();

        return $data;
    }

    public function monthDeal($where){
        $last['first'] = strtotime(date('Y-m-01',time()));//获取指定月份的第一天
        $last['end'] = strtotime(date('Y-m-t',time())); //获取指定月份的最后一天
        $data = $this->select('id','order_number','money','payment','pay_time')
            ->whereBetween('pay_time', [$last['first'], $last['end']])
            ->where($where)
            ->orderBy('id','desc')
            ->take(20)
            ->get();

        return $data;
    }

    public function dayDeal($where){
        $result['start_time'] = strtotime(date('Y-m-d 00:00:00', time()));
        $result['end_time'] = strtotime(date('Y-m-d 23:59:59', time()));

        $data = $this->select('id','order_number','money','payment','pay_time')
            ->whereBetween('pay_time', [$result['start_time'], $result['end_time']])
            ->where($where)
            ->orderBy('id','desc')
            ->take(20)
            ->get();

        return $data;
    }

}