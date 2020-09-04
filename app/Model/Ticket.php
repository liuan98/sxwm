<?php
/**
 * Created by PhpStorm.
 * User: liuan
 * Date: 2020/7/21
 * Time: 10:23
 */
declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
use Hyperf\DbConnection\Db;

/**
 */
class Ticket extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'home_ticket';
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

    public function ticket($uid){
        $data = Ticket::query()
            ->from('home_ticket as t')
            ->leftJoin('admin_coupon as c','t.coupon_id','=','c.id')
            ->select('t.id','c.name','c.full','c.not','t.valid_time')
            ->where('t.status',1)
            ->where('t.uid',$uid)
            ->where('t.valid_time','>',time())
            ->orderBy('t.id','desc')
            ->get();

        return $data;
    }

    public function userCoupon($uid){
        $time = time() - (3600 * 24 * 30);
        $data = Ticket::query()
            ->from('home_ticket as t')
            ->leftJoin('admin_coupon as c','t.coupon_id','=','c.id')
            ->select('t.id','c.name','c.full','c.not','t.valid_time')
            ->where('t.status',2)
            ->where('t.update','>',$time)
            ->where('t.uid',$uid)
            ->orderBy('t.id','desc')
            ->get();

        return $data;
    }

    public function listCoupon($uid){
        $time = time() - (3600 * 24 * 30);

        $data = Ticket::query()
            ->from('home_ticket as t')
            ->leftJoin('admin_coupon as c','t.coupon_id','=','c.id')
            ->select('t.id','c.name','c.full','c.not','t.valid_time')
            ->where('t.status',1)
            ->where('t.uid',$uid)
            ->where('t.valid_time','<',time())
            ->where('t.valid_time','>',$time)
            ->orderBy('t.id','desc')
            ->get();

        return $data;
    }


}

