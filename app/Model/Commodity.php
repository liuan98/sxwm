<?php
/**
 * Created by PhpStorm.
 * User: liuan
 * Date: 2020/7/10
 * Time: 18:01
 */
declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Db;
use Hyperf\DbConnection\Model\Model;
/**
 */
class Commodity extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_commodity';
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

    public function goodsList($where,$little_id,$time){
        if(empty($time)){
            $last['first'] = 0;
            $last['end'] = time();
        }else{
            $last['first'] = strtotime(date('Y-m-d 00:00:00', $time));
            $last['end'] = strtotime(date('Y-m-d 23:59:59', $time));
        }

        if(!empty($little_id)){
            $data = $this->where($where)
                ->whereBetween('add_time', [$last['first'], $last['end']])
                ->where('little_id',$little_id)
                ->orderBy('id','desc')
                ->take(20)
                ->get();
        }else{
            $data = $this->where($where)
                ->whereBetween('add_time', [$last['first'], $last['end']])
                ->orderBy('id','desc')
                ->take(20)
                ->get();
        }

        foreach ($data as $k => $v){
            $list[$k]['id'] = $v['id'];
            $list[$k]['big_id'] = $v['big_id'];
            $list[$k]['little_id'] = $v['little_id'];
            $list[$k]['name'] = $v['name'];
            $list[$k]['title'] = $v['title'];
            $list[$k]['number'] = $v['number'];
            $list[$k]['money'] = $v['money'];
            $list[$k]['price'] = $v['price'];
            $list[$k]['standard'] = $v['standard'];
            $list[$k]['attribute'] = $v['attribute'];
            $list[$k]['label'] = $v['label'];
            $list[$k]['img'] = $v['img'];
            $list[$k]['text'] = $v['text'];
            $list[$k]['status'] = $v['status'];
            $list[$k]['add_time'] = $v['add_time'];
            $list[$k]['update'] = $v['update'];
            $list[$k]['warehouse'] = Db::table('admin_repertory as r')
                ->leftJoin('admin_warehouse as w','w.id','=','r.warehouse_id')
                ->where('r.good_id',$v['id'])
                ->select('r.id','warehouse_id','w.name','r.num')
                ->get();
        }
        return $list;
    }

    public function bargainNew($list){
        $data = Commodity::query()
            ->from('admin_commodity as c')
            ->leftJoin('home_order as o','c.id','=','o.goods_id')
            ->leftJoin('admin_repertory as r','c.id','=','r.good_id')
            ->select('c.id','c.big_id','c.little_id','c.name','c.title','c.number','c.money','c.price','c.standard','c.attribute','c.restrict','c.label','c.img','c.text','c.status','c.add_time','c.update',DB::raw('count(o.goods_id) as count'))
            ->where('c.status',1)
            ->where('c.attribute',1)
            ->where('r.num','>',0)
            ->where('r.warehouse_id',$list['id'])
            ->orderBy('c.id','desc')
            ->groupBy('c.id')
            ->take(3)
            ->get();
        return $data;
    }

    public function bargainList($where,$list){
        $data = Commodity::query()
            ->from('admin_commodity as c')
            ->leftJoin('home_order as o','c.id','=','o.goods_id')
            ->leftJoin('admin_repertory as r','c.id','=','r.good_id')
            ->where($where)
            ->select('c.id','c.big_id','c.little_id','c.name','c.title','c.number','c.money','c.price','c.standard','c.attribute','c.restrict','c.label','c.img','c.text','c.status','c.add_time','c.update',DB::raw('count(o.goods_id) as count'))
            ->where('c.status',1)
            ->where('c.attribute',1)
            ->where('r.num','>',0)
            ->where('r.warehouse_id',$list['id'])
            ->orderBy('c.id','desc')
            ->groupBy('c.id')
            ->take(20)
            ->get();

        return $data;
    }

    public function Recommend($where,$list){
        $data = Commodity::query()
            ->from('admin_commodity as c')
            ->leftJoin('home_order as o','c.id','=','o.goods_id')
            ->leftJoin('admin_repertory as r','c.id','=','r.good_id')
            ->where($where)
            ->select('c.id','c.big_id','c.little_id','c.name','c.title','c.number','c.money','c.price','c.standard','c.attribute','c.restrict','c.label','c.img','c.text','c.status','c.add_time','c.update',DB::raw('count(o.goods_id) as count'))
            ->where('c.status',1)
            ->where('c.attribute',2)
            ->where('r.num','>',0)
            ->where('r.warehouse_id',$list['id'])
            ->orderBy('c.id','desc')
            ->groupBy('c.id')
            ->take(20)
            ->get();

        return $data;
    }


    public function Search($where,$new){
        $data = Commodity::query()
            ->from('admin_commodity as c')
            ->leftJoin('admin_repertory as r','c.id','=','r.good_id')
            ->where($where)
            ->select('c.id','c.big_id','c.little_id','c.name','c.title','c.number','c.money','c.price','c.standard','c.attribute','c.label','c.img','c.text','c.status','c.add_time','c.update')
            ->where('c.status',1)
            ->where('r.num','>',0)
            ->where('r.warehouse_id',$new['id'])
            ->orderBy('c.id','desc')
            ->groupBy('c.id')
            ->take(20)
            ->get();

        return $data;
    }

    public function GoodsNew($v,$new){
        $data = Commodity::query()
            ->from('admin_commodity as c')
            ->leftJoin('admin_repertory as r','c.id','=','r.good_id')
            ->leftJoin('admin_subclass as s','c.little_id','=','s.id')
            ->select('c.id','s.name_little','c.big_id','c.little_id','c.name','c.title','c.number','c.money','c.price','c.standard','c.attribute','c.restrict','c.label','c.img','c.text','c.status','c.add_time','c.update')
            ->where('c.little_id',$v['id'])
            ->where('c.status',1)
            ->where('r.num','>',0)
            ->where('r.warehouse_id',$new['id'])
            ->orderBy('c.id','desc')
            ->groupBy('c.id')
            ->take(20)
            ->get();

        return $data;
    }

    public function goodsPage(){
        $data['num'] = $this->count();

        $data['putaway'] = $this->where('status',1)->count();

        $data['sold'] = $this->where('status',2)->count();
        return $data;
    }


}