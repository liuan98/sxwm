<?php
/**
 * Created by PhpStorm.
 * User: liuan
 * Date: 2020/7/17
 * Time: 13:42
 */
declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 */
class Shopping extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_shopping';
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

    public function shoppingList($time,$where,$status){
        if(empty($time)){
            $last['first'] = 0;
            $last['end'] = time();
        }else{
            $last['first'] = strtotime(date('Y-m-d 00:00:00', $time));
            $last['end'] = strtotime(date('Y-m-d 23:59:59', $time));
        }
        if(empty($status)){
            $data = $this->whereNull('update')
                ->where($where)
                ->whereBetween('add_time', [$last['first'], $last['end']])
                ->take(20)
                ->orderBy('id','desc')
                ->get();
            return $data;
        }
        $data = $this->where('update','>',1)
            ->where($where)
            ->whereBetween('add_time', [$last['first'], $last['end']])
            ->take(20)
            ->orderBy('id','desc')
            ->get();
        return $data;
    }

    public function shoppingExport($status){
        if(empty($status)){
            $data = $this->whereNull('update')
                ->take(1000)
                ->orderBy('id','desc')
                ->get();
            return $data;
        }
        $data = $this->where('update','>',1)
            ->take(1000)
            ->orderBy('id','desc')
            ->get();
        return $data;
    }

}