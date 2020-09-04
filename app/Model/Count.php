<?php
/**
 * Created by PhpStorm.
 * User: liuan
 * Date: 2020/7/17
 * Time: 15:43
 */
declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
use Hyperf\DbConnection\Db;

/**
 */
class Count extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'home_count';
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

    public function InvitedRecord($where,$number,$num){
        $data = Count::query()
            ->from('home_count as c')
            ->leftJoin('home_user as u','c.uid','=','u.id')
            ->select('c.uid','u.username as name','u.phone',DB::raw('count(c.uid) as num'))
            ->where($where)
            ->having('num',$num,$number)
            ->orderBy('num','desc')
            ->groupBy('phone','name')
            ->take(20)
            ->get();

        return $data;
    }

    public function codeNum($user){
        $data = Count::query()
            ->where('uid',$user['id'])
            ->count();

        return $data;
    }

}