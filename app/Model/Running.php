<?php
/**
 * Created by PhpStorm.
 * User: liuan
 * Date: 2020/7/11
 * Time: 17:52
 */
declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 */
class Running extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_running';
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

    public function operation($where,$time){
        if(empty($time)){
            $last['first'] = 0;
            $last['end'] = time();
        }else{
            $last['first'] = strtotime(date('Y-m-d 00:00:00', $time));
            $last['end'] = strtotime(date('Y-m-d 23:59:59', $time));
        }
        $data = Running::query()
            ->from('admin_running as r')
            ->leftJoin('admin_commodity as c','c.id','=','r.good_id')
            ->leftJoin('admin_warehouse as w','w.id','=','r.warehouse_id')
            ->leftJoin('admin_repertory as y','y.warehouse_id','=','r.good_id')
            ->select('r.id','c.number','c.name','w.name as warehouse','y.num as numTwo','r.status','r.num','r.operation','r.add_time')
            ->where($where)
            ->whereBetween('r.add_time', [$last['first'], $last['end']])
            ->orderBy('r.id','desc')
            ->groupBy('r.id')
            ->take(20)
            ->get();
        return $data;

    }
}