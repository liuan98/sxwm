<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 *
 * 用户登陆
 */

namespace App\Controller;

use App\Model\Auth;
use App\Model\User;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\ApiResponse;
use Hyperf\Apidog\Annotation\Body;
use Hyperf\Apidog\Annotation\DeleteApi;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Apidog\Annotation\GetApi;
use Hyperf\Apidog\Annotation\Header;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Apidog\Annotation\Query;

class ApidemoController extends AbstractController
{
    protected $expire = 0;
    protected $authkey = '';

    public function __construct()
    {
        $this->authkey = md5(md5('cwt0627'));
        //$this->expire = 3600 * 3;
    }
    /**
     * @PostApi(path="register", description="用户注册")
     * @Query(key="username|用户id", rule="required")
     * @Query(key="password|密码", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="创建成功", schema={"data":1})
     */
    public function register()
    {
        $data = $this->request->all();
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];
        $message = [
            'username.required' => 'username不能为空',
            'password.required' => 'password不能为空',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $username = $data['username'];
        $password = $data['password'];
        $add = array();
        $salt = random(16);
        $add['username'] = $username;
        $add['password'] = md5($password . $salt);
        $add['salt'] = $salt;
        //获取注册
        $res = User::getInstance()->findData(['username' => $username]);
        if (!$res) {
            $result = User::getInstance()->getRegister($add, $this->authkey, $this->expire);
            if ($result) {
                return success($result);
            } else {
                return fail('注册失败');
            }
        } else {
            return fail('该账号已存在！');
        }
    }
    /**
     * 请注意 body 类型 rules 为数组类型
     * @PostApi(path="login", description="用户登陆")
     * @Query(key="username|用户名称", rule="required|max:150")
     * @Query(key="password|密码", rule="required|max:150")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="登陆成功", schema={"token":1,"expire":1,"info":1})
     */
    public function login()
    {
        $data = $this->request->all();
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];
        $message = [
            'username.required' => 'username不能为空',
            'password.required' => 'password不能为空',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $username = $data['username'];
        $password = $data['password'];
        //获取登陆
        $result = User::getInstance()->getLogin($username, $password, $this->authkey, $this->expire);
        if($result['info']['aid']) $result['role'] = Auth::getInstance()->findData(['id'=>$result['info']['aid']]);
        if ($result) {
            //保存客户端id
            $redis = ApplicationContext::getContainer()->get(\Redis::class);
            $redis->sAdd(config('app_name').'_sjd_1', $result['token']);
            $redis->expire(config('app_name').'_sjd_1', 3600);
            return success($result);
        } else {
            return fail('账号或密码错误！');
        }
    }

    /**
     * @PostApi(path="logout", description="退出登陆")
     * @Query(key="token|接口访问凭证", rule="required")
     * @Query(key="username|用户名称", rule="required|max:150")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="登陆成功", schema={"username":"admin"})
     */
    public function logout()
    {
        $data = $this->request->all();
        $rules = [
            'username' => 'required',
        ];
        $message = [
            'username.required' => 'username不能为空',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $username = $data['username'];
        $useinfo = User::getInstance()->findData(['username'=>$username]);
        if($useinfo) {
            $redis = ApplicationContext::getContainer()->get(\Redis::class);
            //移除集合中指定的value
            $redis->sRem(config('app_name').'_sjd_1', $data['token']);
            return success($username);
        }else{
            return fail('该用户不存在');
        }
    }
    /**
     * @PostApi(path="chgpwd", description="修改个人信息")
     * @Query(key="token|接口访问凭证", rule="required")
     * @Query(key="username|用户名称", rule="required|max:150")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="0", schema={"username":"张三"})
     */
    public function chgpwd()
    {
        $data = $this->request->all();
        $rules = [
            'username' => 'required',
        ];
        $message = [
            'username.required' => 'username不能为空',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $username = $data['username'];
        $useinfo = User::getInstance()->findData(['username' => $username]);
        if (!$useinfo) return fail('该用户不存在');
        if (isset($data['oldpasswd']) && $data['oldpasswd'] && isset($data['newpasswd']) && $data['newpasswd'] && isset($data['repasswd']) && $data['repasswd']) {
            if ($data['newpasswd'] != $data['repasswd']) return fail('新密码和重复密码不一致');
            $find_user = User::getInstance()->findData(['username' => $username, 'password' => md5($data['oldpasswd'] . $useinfo->salt)]);
            if (!$find_user) return fail('原密码不正确');
            $eData['password'] = md5($data['newpasswd'] . $find_user->salt);
            if(isset($data['nickname']) && $data['nickname']) $eData['nickname'] = $data['nickname'];
            $eData['updatetime'] = time();
            User::getInstance()->editData(['username' => $username], $eData);
        } else {
            if(!isset($data['nickname']) || empty($data['nickname'])) return fail('nickname不能为空');
            $eData2['nickname'] = $data['nickname'];
            $eData2['updatetime'] = time();
            User::getInstance()->editData(['username' => $username], $eData2);
        }
        return success($data);
    }
}

