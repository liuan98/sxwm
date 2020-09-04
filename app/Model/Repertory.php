<?php
/**
 * Created by PhpStorm.
 * User: liuan
 * Date: 2020/7/11
 * Time: 16:26
 */
declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 */
class Repertory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_repertory';
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

    public function materials($v){
        $data = objectToArray(Repertory::query()
            ->from('admin_repertory as r')
            ->leftJoin('admin_commodity as c','r.good_id','=','c.id')
            ->where('r.good_id',$v)
            ->select('c.id','c.name','r.num','c.restrict')
            ->first());
        return $data;
    }

}