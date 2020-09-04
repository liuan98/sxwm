<?php
/**
 * Created by PhpStorm.
 * User: liuan
 * Date: 2020/7/20
 * Time: 14:49
 */
declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
use Hyperf\DbConnection\Db;

/**
 */
class Vehicle extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'home_vehicle';
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

    public function vehicleList($uid){
        $data = Vehicle::query()
            ->from('home_vehicle as v')
            ->leftJoin('admin_commodity as c','v.goods_id','=','c.id')
            ->where('v.uid',$uid)
            ->select('v.id','v.goods_id','c.name','c.title','c.number','c.money','c.price','c.standard','c.attribute','c.label','c.img','c.text','c.status',DB::raw('sum(v.num) as num'))
            ->groupBy('v.goods_id')
            ->get();
        return $data;
    }
}