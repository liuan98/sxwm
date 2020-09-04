<?php
/**
 * Created by PhpStorm.
 * User: liuan
 * Date: 2020/7/16
 * Time: 17:35
 */
declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 */
class Evaluate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'home_evaluate';
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

    public function evaluateList($where,$name,$time,$status){
        if(empty($time)){
            $last['first'] = 0;
            $last['end'] = time();
        }else{
            $last['first'] = strtotime(date('Y-m-d 00:00:00', $time));
            $last['end'] = strtotime(date('Y-m-d 23:59:59', $time));
        }
        if(empty($status)){
            $status = 5;
        }
        if($status > 3){
            $query = $this->where($where);
            $query->where(function ($query) use ($name){
                $query->where('phone', 'like', '%'.$name.'%')
                    ->orWhere('name', 'like', '%'.$name.'%');
            });
            $query->whereBetween('add_time', [$last['first'], $last['end']]);
            $query->take(20);
            $query->orderBy('id','desc');
            $data = $query->get();

            return $data;
        }else{
            $query = $this->where($where);
            $query->where(function ($query) use ($name){
                $query->where('phone', 'like', '%'.$name.'%')
                    ->orWhere('name', 'like', '%'.$name.'%');
            });
            $query->where('status',$status);
            $query->whereBetween('add_time', [$last['first'], $last['end']]);
            $query->take(20);
            $query->orderBy('id','desc');
            $data = $query->get();

            return $data;
        }

    }

    public function evaluate($num,$where,$goods_id){
        $data = $this->where($where)
            ->where('goods_id',$goods_id)
            ->where('status',2)
            ->take($num)
            ->orderBy('id','desc')
            ->get();

        return $data;
    }

    public function count($goods_id){
        $data = $this->where('goods_id',$goods_id)
            ->where('status',2)
            ->count();

        return $data;
    }

}