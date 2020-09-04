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

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use App\Model\Auth;
use App\Model\Cate;
use App\Model\Item;
use App\Model\Logo;
use App\Model\News;
use App\Model\User;
use Hyperf\Utils\ApplicationContext;
use Hyperf\View\RenderInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

class IndexController extends AbstractController
{
    protected $expire = 0;
    protected $authkey = '';

    public function __construct()
    {
        $this->authkey = md5(md5('cwt0627'));
        //$this->expire = 3600 * 3;
    }

    /**
     * @return array
     * 首页测试
     */
    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $Method = $this->request->getMethod();
        return [
            'method' => $Method,
            'message' => "Hello  {$user}.",
            'message2' => str_replace($this->request->getRequestTarget(),'',$this->request->getUri()),
        ];
    }

    /**
     * @param RenderInterface $render
     * @return mixed
     * 前台文件渲染
     */
    public function dist(RenderInterface $render)
    {
        return $render->render('dist',[]);
    }

    /**
     * @param RenderInterface $render
     * @return mixed
     * 后台文件渲染
     */
    public function back(RenderInterface $render)
    {
        return $render->render('back',[]);
    }

    /**
     * 建站注册
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
     * 建站登陆2
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
     * 退出登陆
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
     * @param $token
     * @return bool|false|string
     * 解密token
     */
   public function getToken($token)
   {
       if (!empty($token)) {
           $token = authcode(base64_decode($token), 'DECODE', $this->authkey, $this->expire);
           if (!empty($token)) {
               return $token;
           }
           return false;
       }
   }

    /**
     * @return array
     * 登陆背景图片
     */
    public function indexImg(){
        $info = Logo::getInstance()->findData(['status'=>0]);
        return success($info);
    }

    /**
     * @return array
     * 类别
     */
    public function cate(){
        $data = $this->request->all();
        $rules = [
            'pid' => 'required|numeric',
        ];
        $message = [
            'pid.required' => 'pid不能为空',
            'pid.numeric' => 'pid是数字类型',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        //分类
        $data1['cate'] = Cate::getInstance()->getList(['type'=>1,'parent_id'=>0,'pid'=>$data['pid']]);
        if(!$data1['cate']) return success(array());
        foreach ($data1['cate'] as $k => $v) {
            $arr2 = Cate::getInstance()->getList(['parent_id' => $v['id']]);
            if(!$arr2) $arr2=array();
            $data1['cate'][$k]['child'] = $arr2;
        }
        return success($data1);
    }

    /**
     * @return array
     * 首页数据22
     */
    public function list(){
        $data = $this->request->all();
        $rules = [
            'pid' => 'required|numeric',
        ];
        $message = [
            'pid.required' => 'pid不能为空',
            'pid.numeric' => 'pid是数字类型',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $offset = (isset($data['page']) && $data['page'] > 0) ? $data['page'] : 1;
        $params = 10;
        $search = (isset($data['search']) && $data['search']) ? $data['search'] : '';
        //传小分类的id
        $pid = (isset($data['pdid']) && $data['pdid']) ? jsonToArray($data['pdid']) : '';
        $data1['info'] = News::query()->where(function ($query) use ($pid,$search,$data) {
            $query->where(['status' => 1,'pid'=>$data['pid']]);
            if($search) $query->whereRaw("`name` like '%" . $search . "%'");
            if ($pid) {
                foreach ($pid as $key=>$val) {
                    $query->whereRaw('FIND_IN_SET('.$val.',parentid)');
                }
            }
        })->orderBy('update_time', 'DESC')->offset(($offset -1)*$params)->limit($params)->get();
        $data1['total'] = News::query()->where(function ($query) use ($pid,$search,$data) {
            $query->where(['status' => 1,'pid'=>$data['pid']]);
            if($search) $query->whereRaw("`name` like '%" . $search . "%'");
            if ($pid) {
                foreach ($pid as $key=>$val) {
                    $query->whereRaw('FIND_IN_SET('.$val.',parentid)');
                }
            }
        })->count();
        $data1['page'] = $offset;
        $data1['params'] = $params;
        if(count(objectToArray($data1['info'])) <= 0) $data1['info'] = array();
        //处理分类
        if(count(objectToArray($data1['info'])) > 0) {
            foreach ($data1['info'] as $kk => $vv) {
                $data1['info'][$kk]['chd'] = array();
                $c_child = $vv['parentid']?explode(',',$vv['parentid']):$vv['parentid'];
                if(is_array($c_child)) {
                    foreach ($c_child as $kk2=>$vv2) {
                        $caData = Cate::query()->whereIn('id',$c_child)->get();
                        $data1['info'][$kk]['chd'] = $caData;
                    }
                }else{
                    if($c_child){
                        $caData2 = Cate::query()->where('id',$c_child)->get();
                        $data1['info'][$kk]['chd'] = $caData2;
                    }
                }
                $c_child = array();
            }
        }
        //返回大类
        $data1['pcate'] = Cate::getInstance()->getList(['parent_id'=>0,'type'=>1]);
        return success($data1);
    }

    /**
     * @return array
     * 页面
     */
    public function detail(){
        $getItem = Item::getInstance()->getData(['i.type'=>1]);
        return success($getItem);
    }

    /**
     * @return array
     * 修改个人信息
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

    /**
     * @return array
     * 登录token验证
     */
    public function checktoken()
    {
        $token = $this->request->input('token');
        if($token) {
            $token = $this->getToken($token);
            if ($token) {
                return success(['token' => $token]);
            } else {
                return fail('token失效');
            }
        }
        return fail('缺少参数');
    }
    /**
     * 短信验证码
     */
    public function sendMessage(){
        # 获取参数
        $params = $this->request->all();
        if(!isset($params['username'])) return fail( '用户名不能为空！');
        $redis = $this->container->get(\Hyperf\Redis\Redis::class);
        try {
            AlibabaCloud::accessKeyClient('LTAI4Fgt86wVLXe6wtQ3LLAA', 'iPEcAffilkftsbGiTulbp07sN30BAq')
                ->regionId('cn-hangzhou')
                ->asDefaultClient();
            $code = randcode(6);
            AlibabaCloud::rpc()
                ->product('Dysmsapi')
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'PhoneNumbers' => $params['username'],
                        'SignName' => "身份验证",
                        'TemplateCode' => "SMS_183775197",
                        'TemplateParam' => json_encode(['code' => $code]),
                    ],
                ])->request();
            $redis->set('code'.$params['username'],$code,180);
        } catch (ClientException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        }
    }
}

