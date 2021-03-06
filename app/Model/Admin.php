<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\DbConnection\Model\Model;
/**
 */
class Admin extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'db_admin';
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

    /**
     * @param $where
     * @return bool|\Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     * 查询一条
     */
    public function findData($where){
        $data = self::query()->where($where)->first();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    /**
     * @param $username
     * @param $password
     * 登陆验证
     */
    public function getLogin($username,$password,$key,$expire){

        $res = self::query()->where(['username'=>$username])->first();
        if ($res) {
            $find_user = self::query()->where(['username'=>$username,'password'=>md5($password.$res->salt)])->first();
            if($find_user) {
                $token = base64_encode(authcode($username . '|' . $find_user->salt, 'ENCODE', $key,$expire));
                $data = array(
                    'token' => $token,
                    'expire' => $expire,
                    'info' => array('username' => $username,'nickname'=>$find_user->nickname)
                );
                return $data;
            }else{
                return false;
            }
        } else {
            return false;
        }

    }

    /**
     * @param $data
     * @param $key
     * @param $expire
     * @return array|bool
     * 注册
     */
    public function getRegister($data,$key,$expire)
    {

        $res2 = self::query()->insertGetId($data);
        if ($res2) {
            $token = base64_encode(authcode($data['username'] . '|' . $data['salt'], 'ENCODE', $key, $expire));
            $info = array(
                'token' => $token,
                'expire' => $expire,
                'info' => array('username' => $data['username'])
            );
            return $info;
        } else {
            return false;
        }
    }


    /**
     * @param $add
     * @return bool|int
     * 添加
     */
    public function addData($add){
        $data = self::query()->insertGetId($add);
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    /**
     * @param $where
     * @param $add
     * @return bool
     * 编辑
     */
    public function editData($where,$add){
        $data = self::query()->where($where)->update($add);
        if($data !== false){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $where
     * @return bool
     * 删除
     */
    public function delData($where){
        $data = self::query()->where($where)->delete();
        if($data){
            return true;
        }else{
            return false;
        }
    }

}