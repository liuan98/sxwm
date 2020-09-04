<?php
/**
 * Created by PhpStorm.
 * User: liuan
 * Date: 2020/7/16
 * Time: 10:52
 */
declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 */
class Img extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_img';
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

    public function imgList($where){
        $data = $this->where($where)
            ->orderBy('sort','desc')
            ->take(20)
            ->get();
        return $data;
    }


    public function pageList(){
        $data = $this->where('status',1)
            ->orderBy('sort','desc')
            ->get();
        return $data;
    }

}