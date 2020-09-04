<?php
/**
 * Created by PhpStorm.
 * User: liuan
 * Date: 2020/7/9
 * Time: 20:15
 */
declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
use Hyperf\DbConnection\Db;
/**
 */
class Adminuser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_user';
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

    public function adminLogin($account,$password){
        $data = objectToArray($this->where('account',$account)
            ->where('password',$password)
            ->first());
        return $data;
    }

    public function userList(){
        $data = $this->get();

        foreach ($data as $k => $v){
            $list[$k]['id'] = $v['id'];
            $list[$k]['username'] = $v['username'];
            $list[$k]['account'] = $v['account'];
            $list[$k]['password'] = $v['password'];
            $list[$k]['auth'] = Db::table('admin_auth')->whereIn('id',explode(',',$v['auth']))->select('id','name','url')->get();
            $list[$k]['add_time'] = $v['add_time'];
            $list[$k]['update'] = $v['update'];
            $list[$k]['finallytime'] = $v['finallytime'];
        }
        return $list;

    }
}