<?php
/**
 * Created by PhpStorm.
 * User: liuan
 * Date: 2020/7/20
 * Time: 10:01
 */
declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Db;
use Hyperf\DbConnection\Model\Model;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

/**
 */
class Order extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'home_order';
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

    public function stayList($order_number){
        $data = Confirm::query()
            ->from('home_order as o')
            ->leftJoin('admin_commodity as c','o.goods_id','=','c.id')
            ->select('c.name','c.img','c.standard','c.money','o.number')
            ->where('o.order',$order_number)
            ->groupBy('o.id')
            ->get();
        return $data;
    }

    public function statistics(){
        $result['start_time'] = strtotime(date('Y-m-d 00:00:00', time()));
        $result['end_time'] = strtotime(date('Y-m-d 23:59:59', time()));
        $data = Order::query()
            ->from('home_order as o')
            ->leftJoin('admin_commodity as c','o.goods_id','=','c.id')
            ->select('o.goods_id','c.name',DB::raw('sum(o.number) as number'))
            ->whereBetween('o.add_time', [$result['start_time'], $result['end_time']])
            ->groupBy('c.name')
            ->get();

        return $data;
    }

    public function listName($where){
        $time = time() - (3600 * 24 * 30);
        $data = Order::query()
            ->from('home_order as o')
            ->leftJoin('admin_commodity as c','o.goods_id','=','c.id')
            ->select('o.goods_id','c.name',DB::raw('sum(o.number) as number'))
            ->where($where)
            ->where('o.add_time','>',$time)
            ->groupBy('c.name')
            ->get();

        return $data;
    }

    public function listTime($time){
        $last['first'] = strtotime(date('Y-m-d 00:00:00', $time));
        $last['end'] = strtotime(date('Y-m-d 23:59:59', $time));

        $data = Order::query()
            ->from('home_order as o')
            ->leftJoin('admin_commodity as c','o.goods_id','=','c.id')
            ->select('o.goods_id','c.name',DB::raw('sum(o.number) as number'))
            ->whereBetween('o.add_time', [$last['first'], $last['end']])
            ->orderBy('number','desc')
            ->groupBy('c.name')
            ->take(30)
            ->get();
        var_dump($data);

        return $data;
    }

    public function stati($time,$where){
        $last['first'] = strtotime(date('Y-m-d 00:00:00', $time));
        $last['end'] = strtotime(date('Y-m-d 23:59:59', $time));

        $data = Order::query()
            ->from('home_order as o')
            ->leftJoin('admin_commodity as c','o.goods_id','=','c.id')
            ->select('o.goods_id','c.name',DB::raw('sum(o.number) as number'))
            ->whereBetween('o.add_time', [$last['first'], $last['end']])
            ->where($where)
            ->orderBy('number','desc')
            ->groupBy('c.name')
            ->get();

        return $data;
    }

    public function orderNew($pay){
        $data = Order::query()
            ->from('home_order as o')
            ->leftJoin('admin_commodity as c','o.goods_id','=','c.id')
            ->where('o.order',$pay['order_number'])
            ->select('o.id','c.name','c.img','c.standard','c.number','c.money','o.number',DB::raw('sum(c.money * o.number) as price'))
            ->groupBy('o.order','o.goods_id')
            ->get();

        return $data;
    }

}