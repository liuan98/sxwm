<?php
/**
 * Created by PhpStorm.
 * User: liuan
 * Date: 2020/7/10
 * Time: 14:05
 */
declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 */
class Warehouse extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_warehouse';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
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

    public function location($location){
        $data = objectToArray(Warehouse::query()
            ->from('admin_warehouse as w')
            ->leftJoin('admin_repertory as r','w.id','=','r.warehouse_id')
            ->where('w.area_name',$location)
            ->select('w.id')
            ->first());
        return $data;
    }


    public function goodsList($data){
        $data = objectToArray($this->where('area_name',$data['location'])
            ->select('id')
            ->first());
        return $data;
    }


}