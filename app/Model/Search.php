<?php
/**
 * Created by PhpStorm.
 * User: liuan
 * Date: 2020/7/18
 * Time: 17:06
 */
declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Db;
use Hyperf\DbConnection\Model\Model;
/**
 */
class Search extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'home_search';
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

    public function HotBot(){
        $data = $this->select('id','name',DB::raw('count(name) as num'))
            ->orderBy('num','desc')
            ->groupBy('name')
            ->take(10)
            ->get();

        return $data;
    }

}