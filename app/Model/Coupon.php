<?php
/**
 * Created by PhpStorm.
 * User: liuan
 * Date: 2020/7/17
 * Time: 9:46
 */
declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 */
class Coupon extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_coupon';
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

    public function couponList($where){
        $data = $this->where($where)
            ->where('count','>',0)
            ->take(20)
            ->orderBy('id','desc')
            ->get();

        return $data;
    }

    public function coupon($where){
        $data = $this->where($where)
            ->take(20)
            ->orderBy('id','desc')
            ->get();

        return $data;
    }


    public function discounts($data){
        $data = objectToArray(Coupon::query()
            ->from('admin_coupon as c')
            ->leftJoin('home_ticket as t','c.id','=','t.coupon_id')
            ->where('t.uid',$data['uid'])
            ->where('t.id',$data['discounts'])
            ->select('t.id','c.full','c.not')
            ->first());

        return $data;
    }

    public function couponCode(){
        $data = $this->where('way','>',0)
            ->where('count','>',0)
            ->orderBy('id','desc')
            ->get();

        return $data;

    }

    public function centreCoupon(){
        $data = $this->where('way','==',0)
            ->where('count','>',0)
            ->orderBy('id','desc')
            ->get();

        return $data;

    }

}