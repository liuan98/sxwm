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
use App\Model\Cate;
use App\Model\Item;
use App\Model\Logo;
use App\Model\News;
use App\Model\User;
use App\Service\BackService;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

class BackController extends AbstractController
{
    protected $expire = 0;
    protected $authkey = '';

    public function __construct()
    {
        $this->authkey = md5(md5('cwt0627'));
        //$this->expire = 3600 * 3;
    }
    /**
     * 后台登陆
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
            $redis->sAdd(config('app_name').'_backsjd_1', $result['token']);
            $redis->expire(config('app_name').'_backsjd_1', 1800);
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
            $redis->sRem(config('app_name').'_backsjd_1', $data['token']);
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
     * @return array
     * todo:页面管理列表
     */
    public function itemList(){
        $getItem = Item::getInstance()->getData(['i.type'=>1]);
        return success($getItem);
    }
    /**
     * @return array
     * 页面管理新增
     */
    public function itemAdd(){
        $data = $this->request->all();
        $rules = [
            'order' => 'required|numeric',
            'tname' => 'required|max:20',
            'show' => 'required|numeric',
        ];
        $message = [
            'order.required' => 'order不能为空',
            'order.numeric' => 'order是数字类型',
            'tname.required' => 'tname不能为空',
            'show.required' => 'show不能为空',
            'show.numeric' => 'show是数字类型',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $findTotal = Item::getInstance()->countItem(['type'=>1]);
        $num = isset($findTotal->num)?$findTotal->num:0;
        if($num>=5) return fail('最多5个页面，无法继续新增哦');
        $arr = array();
        $arr['order'] = $data['order'];
        $arr['tname'] = $data['tname'];
        $arr['show'] = $data['show'];
        $arr['order'] = $data['order'];
        $arr['createtime'] = time();
        $arr['updatetime'] = time();
        $add = Item::getInstance()->addData($arr);
        if($add) {
            $slA = Auth::getInstance()->getList(['status'=>0]);
            foreach ($slA as $key=>$val) {
                $ed['role'] = substr($val['role'],0,24).str_replace(substr($val['role'],0,24),'',substr($val['role'],0,-15)).'-'.$add.substr($val['role'],-16);
                $ed['updatetime'] = time();
                Auth::getInstance()->editData(['id'=>$val['id']],$ed);
            }
            return success($add);
        }else{
            return fail('失败');
        }
    }
    /**
     * @return array
     * 页面管理信息
     */
    public function itemInfo(){
        $data = $this->request->all();
        $rules = [
            'id' => 'required|numeric',
        ];
        $message = [
            'id.required' => 'id不能为空',
            'id.numeric' => 'id是数字类型',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $info = Item::getInstance()->findData(['id'=>$data['id']]);
        if(!$info) return fail('该信息不存在');
        return success($info);
    }
    /**
     * @return array
     * 页面编辑
     */
    public function itemEdit(){
        $data = $this->request->all();
        $rules = [
            'id' => 'required|numeric',
            'order' => 'required|numeric',
            'tname' => 'required|max:20',
            'show' => 'required|numeric',
        ];
        $message = [
            'id.required' => 'id不能为空',
            'id.numeric' => 'id是数字类型',
            'order.required' => 'order不能为空',
            'order.numeric' => 'order是数字类型',
            'tname.required' => 'tname不能为空',
            'show.required' => 'show不能为空',
            'show.numeric' => 'show是数字类型',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $arr['order'] = $data['order'];
        $arr['tname'] = $data['tname'];
        $arr['show'] = $data['show'];
        $arr['updatetime'] = time();
        Item::getInstance()->editData(['id'=>$data['id']],$arr);
        return success($data['id']);
    }
    /**
     * @return array
     * 页面管理删除
     */
    public function itemDel(){
        $data = $this->request->all();
        $rules = [
            'id' => 'required|numeric',
        ];
        $message = [
            'id.required' => 'id不能为空',
            'id.numeric' => 'id是数字类型',
        ];//页面id
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $fNews = News::getInstance()->findData(['pid'=>$data['id'],'status'=>1]);
        $fCate = Cate::getInstance()->findData(['pid'=>$data['id'],'type'=>1,'parent_id'=>0]);
        if($fNews || $fCate) return fail('该页面下已有产品或有分类，请先清空');
        Item::getInstance()->delData(['id'=>$data['id']]);
        $slA = Auth::getInstance()->getList(['status'=>0]);
        foreach ($slA as $key=>$val) {
            $ed['role'] = str_replace(array("{$data['id']}".',',"{$data['id']}",'-'."{$data['id']}".',','-'."{$data['id']}"),'',$val['role']);
            $ed['updatetime'] = time();
            Auth::getInstance()->editData(['id'=>$val['id']],$ed);
        }
        return success($data);
    }
    /**
     * 登陆页保存
     */
    public function logoEdit(){
        $data = $this->request->all();
        $rules = [
            'img1' => 'required',
            'img2' => 'required',
            'img3' => 'required',
            'img4' => 'required',
        ];
        $message = [
            'img1.required' => 'img1不能为空',
            'img2.required' => 'img2不能为空',
            'img3.required' => 'img3不能为空',
            'img4.required' => 'img4不能为空',
        ];//页面id
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $arr['img1'] = $data['img1'];
        $arr['img2'] = $data['img2'];
        $arr['img3'] = $data['img3'];
        $arr['img4'] = $data['img4'];
        $arr['updatetime'] = time();
        Logo::getInstance()->editData(['id'=>1],$arr);
        return success($data);
    }
    /**
    * 页面管理分类列表
    */
    public function cateList(){
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
        $arr = Cate::getInstance()->getList(['type'=>1,'parent_id'=>0,'pid'=>$data['pid']]);
        if(!$arr) return success(array());
        foreach ($arr as $k => $v) {
            $arr2 = Cate::getInstance()->getList(['parent_id' => $v['id']]);
            if(!$arr2) $arr2=array();
            $arr[$k]['child'] = $arr2;
        }
        return success($arr);
    }
    /**
     * 页面管理分类添加
     */
    public function cateAdd()
    {
        $data = $this->request->all();
        $rules = [
            'ppid' => 'required|numeric',
            'name' => 'required',
        ];
        $message = [
            'ppid.required' => 'ppid不能为空',
            'ppid.numeric' => 'ppid是数字类型',
            'name.required' => 'name不能为空',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $arr['tname'] = $data['name'];
        $arr['pid'] = $data['ppid'];
        $arr['parent_id'] = (isset($data['pid']) && $data['pid']) ? $data['pid'] : 0;
        $arr['createtime'] = time();
        $arr['updatetime'] = time();
        $addD = Cate::getInstance()->addData($arr);
        if(!$addD) return fail('添加失败');
        return success($addD);
    }
    /**
     * 页面管理分类编辑
     */
    public function cateEdit(){
        $data = $this->request->all();
        $rules = [
            'id' => 'required|numeric',
            'name' => 'required',
        ];
        $message = [
            'id.required' => 'id不能为空',
            'id.numeric' => 'id是数字类型',
            'name.required' => 'name不能为空',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $arr['tname'] = $data['name'];
        $arr['updatetime'] = time();
        Cate::getInstance()->editData(['id'=>$data['id']],$arr);
        return success($data);
    }
    /**
     * 页面管理分类删除
     */
    public function cateDel(){
        $data = $this->request->all();
        $rules = [
            'id' => 'required|numeric',
        ];
        $message = [
            'id.required' => 'id不能为空',
            'id.numeric' => 'id是数字类型',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $del = Cate::getInstance()->delData(['id'=>$data['id']]);
        Cate::getInstance()->delData(['parent_id'=>$data['id']]);
        if(!$del) return fail('操作失败');
        $selD = News::getInstance()->getList(['status'=>1]);
        if(!$selD) return success($data);
        //清理属性
        foreach ($selD as $val){
            $arpid = array();
            if($val['parentid']) {
                $dd = BackService::getInstance()->makeArray($val['parentid'], $data['id']);
                if($dd) {
                    $arpid['parentid'] = $val['parentid'] ?$dd: '';
                    News::getInstance()->editData(['id' => $val['id']], $arpid);
                }
            }
        }
        return success($data);
    }
    /**
     * 页面管理产品列表
     */
    public function productList(){
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
        $pid[0] = (isset($data['pdid']) && $data['pdid']) ? $data['pdid'] : '';
        $data1['info'] = News::query()->where(function ($query) use ($pid,$search,$data) {
            $query->where(['status' => 1,'pid'=>$data['pid']]);
            if($search) $query->whereRaw("`name` like '%" . $search . "%'");
            if($pid[0]) $query->whereIn('parentid',$pid);
        })->orderBy('update_time', 'DESC')->offset(($offset -1)*$params)->limit($params)->get();
        $data1['total'] = News::query()->where(function ($query) use ($pid,$search,$data) {
            $query->where(['status' => 1,'pid'=>$data['pid']]);
            if($search) $query->whereRaw("`name` like '%" . $search . "%'");
            if($pid[0]) $query->whereIn('parentid',$pid);
        })->count();
        if(count(objectToArray($data1['info'])) <= 0) $data1['info'] = array();
        //处理分类
        if(count(objectToArray($data1['info'])) > 0) {
            foreach ($data1['info'] as $kk => $vv) {
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
        $data1['cate'] = Cate::getInstance()->getList(['parent_id'=>0,'type'=>1]);
        return success($data1);
    }
    /**
     * 页面管理产品添加
     */
    public function productAdd(){
        $data = $this->request->all();
        $rules = [
            'pid' => 'required|numeric',
            'name' => 'required',
            'money' => 'required',
            'remark' => 'max:150',
            'site' => 'max:150',
            'channel' => 'max:150',
            'discount' => 'max:200',
        ];
        $message = [
            'pid.required' => 'pid不能为空',
            'pid.numeric' => 'pid是数字类型',
            'name.required' => 'name不能为空',
            'money.required' => 'money不能为空',
            'remark.max' => '备注长度不得超过150字符',
            'site.max' => '备注长度不得超过150字符',
            'channel.max' => '备注长度不得超过150字符',
            'discount.max' => '备注长度不得超过200字符',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $arr = array();
        $arr['pid'] = $data['pid'];
        $arr['name'] = $data['name'];
        $arr['parentid'] = (isset($data['parentid']) && $data['parentid'])?implode(',',$data['parentid']):'';
        $arr['thumb'] = (isset($data['thumb']) && $data['thumb'])?$data['thumb']:'';
        $arr['thumb_one'] = (isset($data['thumb_one']) && $data['thumb_one'])?$data['thumb_one']:'';
        $arr['url_link'] = (isset($data['url_link']) && $data['url_link'])?$data['url_link']:'';
        $arr['money'] = $data['money'];
        $arr['click'] = (isset($data['click']) && $data['click'])?$data['click']:'';
        $arr['fans'] = (isset($data['fans']) && $data['fans'])?$data['fans']:'';
        $arr['remark'] = (isset($data['remark']) && $data['remark'])?$data['remark']:'';
        $arr['s_status'] = (isset($data['s_status']) && $data['s_status'])?$data['s_status']:3;
        $arr['channel'] = (isset($data['channel']) && $data['channel'])?$data['channel']:'';
        $arr['site'] = (isset($data['site']) && $data['site'])?$data['site']:'';//位置
        $arr['discount'] = (isset($data['discount']) && $data['discount'])?$data['discount']:'';//折扣
        $arr['insid'] = (isset($data['insid']) && $data['insid'])?$data['insid']:'';//页面增加id
        $arr['add_time'] = time();
        $arr['update_time'] = time();
        $addN = News::getInstance()->addData($arr);
        if(!$addN) return fail('操作失败');
        return success($addN);
    }
    /**
     * 获取页面管理产品信息
     */
    public function productInfo()
    {
        $data = $this->request->all();
        $rules = [
            'id' => 'required|numeric',
        ];
        $message = [
            'id.required' => 'id不能为空',
            'id.numeric' => 'id是数字类型',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $data1 = News::query()->where(function ($query) use ($data) {
            $query->where(['status' => 1, 'id' => $data['id']]);
        })->first();
        if (count(objectToArray($data1['info'])) <= 0) $data1['info'] = array();
        //处理分类


        $c_child = isset($data1->parentid) ? explode(',', $data1->parentid) : $data1->parentid;
        if (is_array($c_child)) {
            foreach ($c_child as $kk2 => $vv2) {
                $caData = Cate::query()->whereIn('id', $c_child)->get();
                $data1['chd'] = $caData;
            }
        } else {
            if ($c_child) {
                $caData2 = Cate::query()->where('id', $c_child)->get();
                $data1['chd'] = $caData2;
            }
        }
        return success($data1);
    }
    /**
     * 页面管理产品编辑
     */
    public function productEdit(){
        $data = $this->request->all();
        $rules = [
            'id' => 'required|numeric',
            'name' => 'required',
            'money' => 'required',
            'remark' => 'max:150',
            'site' => 'max:150',
            'channel' => 'max:150',
            'discount' => 'max:200',
        ];
        $message = [
            'id.required' => 'id不能为空',
            'id.numeric' => 'id是数字类型',
            'name.required' => 'name不能为空',
            'money.required' => 'money不能为空',
            'remark.max' => '备注长度不得超过150字符',
            'site.max' => '备注长度不得超过150字符',
            'channel.max' => '备注长度不得超过150字符',
            'discount.max' => '备注长度不得超过200字符',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $arr = array();
        $arr['name'] = $data['name'];
        $arr['parentid'] = (isset($data['parentid']) && $data['parentid'])?implode(',',$data['parentid']):'';
        $arr['thumb'] = (isset($data['thumb']) && $data['thumb'])?$data['thumb']:'';
        $arr['thumb_one'] = (isset($data['thumb_one']) && $data['thumb_one'])?$data['thumb_one']:'';
        $arr['url_link'] = (isset($data['url_link']) && $data['url_link'])?$data['url_link']:'';
        $arr['money'] = $data['money'];
        $arr['click'] = (isset($data['click']) && $data['click'])?$data['click']:'';
        $arr['fans'] = (isset($data['fans']) && $data['fans'])?$data['fans']:'';
        $arr['remark'] = (isset($data['remark']) && $data['remark'])?$data['remark']:'';
        $arr['s_status'] = (isset($data['s_status']) && $data['s_status'])?$data['s_status']:'';
        $arr['channel'] = (isset($data['channel']) && $data['channel'])?$data['channel']:'';
        $arr['site'] = (isset($data['site']) && $data['site'])?$data['site']:'';//位置
        $arr['discount'] = (isset($data['discount']) && $data['discount'])?$data['discount']:'';//折扣
        $arr['insid'] = (isset($data['insid']) && $data['insid'])?$data['insid']:'';//页面增加id
        $arr['update_time'] = time();
        $addN = News::getInstance()->editData(['id'=>$data['id']],$arr);
        return success($data);
    }
    /**
     * 页面管理产品删除
     */
    public function productDel(){
        $data = $this->request->all();
        $rules = [
            'id' => 'required|numeric',
        ];
        $message = [
            'id.required' => 'id不能为空',
            'id.numeric' => 'id是数字类型',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $del = News::getInstance()->delData(['id'=>$data['id']]);
        if(!$del) return fail('操作失败');
        return success($data);
    }
    /**
     * 页面管理角色列表
     */
    public function authList(){
        $info = Auth::getInstance()->getList(['status'=>0]);
        return success($info);
    }
    /**
     * 页面管理角色添加
     */
    public function authAdd(){
        $data = $this->request->all();
        $rules = [
            'name' => 'required',
            'role' => 'required',
        ];
        $message = [
            'name.required' => 'name不能为空',
            'role.required' => 'role不能为空',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $arr = array();
        $arr['name'] = $data['name'];
        $arr['role'] = $data['role'];
        $arr['createtime'] = time();
        $arr['updatetime'] = time();
        $addA = Auth::getInstance()->addData($arr);
        if(!$addA) return fail('操作失败');
        return success($addA);
    }
    /**
     * 获取页面管理角色信息
     */
    public function authInfo(){
        $data = $this->request->all();
        return success($data);
    }
    /**
     * 页面管理角色编辑
     */
    public function authEdit(){
        $data = $this->request->all();
        $rules = [
            'id' => 'required|numeric',
            'name' => 'required',
            'role' => 'required',
        ];
        $message = [
            'id.required' => 'id不能为空',
            'id.numeric' => 'id是数字类型',
            'name.required' => 'name不能为空',
            'role.required' => 'money不能为空',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $arr = array();
        $arr['name'] = $data['name'];
        $arr['role'] = $data['role'];
        $arr['updatetime'] = time();
        Auth::getInstance()->editData(['id'=>$data['id']],$arr);
        return success($data);
    }
    /**
     * 页面管理角色删除
     */
    public function authDel(){
        $data = $this->request->all();
        $rules = [
            'id' => 'required|numeric',
        ];
        $message = [
            'id.required' => 'id不能为空',
            'id.numeric' => 'id是数字类型',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $find = User::getInstance()->findData(['aid'=>$data['id']]);
        if($find) return fail('该角色下有用户，请清空用户后再尝试操作!');
        $del = Auth::getInstance()->delData(['id'=>$data['id']]);
        if(!$del) return fail('操作失败');
        return success($data);
    }

    /**
     * 页面管理用户列表
     */
    public function consumerList()
    {
        $data = $this->request->all();
        $offset = (isset($data['page']) && $data['page'] > 0) ? $data['page'] : 1;
        $params = 10;
        $search = (isset($data['search']) && $data['search']) ? $data['search'] : '';
        $info = User::query()->select('id','aid','username','nickname')->where(function ($query) use ($search) {
            $query->where(['status' => 0]);
            if($search) $query->whereRaw("concat(`username`,`nickname`) like '%" . $search . "%'");
        })->orderBy('updatetime', 'DESC')->offset(($offset -1)*$params)->limit($params)->get();
        foreach ($info as $kk => $vv){
            $info[$kk]['a_name'] = Auth::query()->where(['id'=>$vv['aid']])->value('name');
        }
        return success($info);
    }
    /**
     * 页面管理用户添加
     */
    public function consumerAdd(){
        $data = $this->request->all();
        $rules = [
            'username' => 'required',
            'nickname' => 'required',
            'password' => 'required',
            'aid' => 'required|numeric',
        ];
        $message = [
            'username.required' => 'username不能为空',
            'nickname.required' => 'nickname不能为空',
            'password.required' => 'password不能为空',
            'aid.required' => 'aid不能为空',
            'aid.numeric' => 'aid是数字类型',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $find = User::getInstance()->findData(['username'=>$data['username']]);
        if($find) return fail('该用户已存在');
        $arr = array();
        $salt = random(16);
        $arr['username'] = $data['username'];
        $arr['nickname'] = $data['nickname'];
        $arr['password'] = md5($data['password'] . $salt);
        $add['salt'] = $salt;
        $arr['aid'] = $data['aid'];
        $arr['createtime'] = time();
        $arr['updatetime'] = time();
        $addA = User::getInstance()->addData($arr);
        if(!$addA) return fail('操作失败');
        return success($addA);
    }
    /**
     * 获取管理用户信息
     */
    public function consumerInfo(){
        $data = $this->request->all();
        return success($data);
    }
    /**
     * 页面管理用户编辑
     */
    public function consumerEdit(){
        $data = $this->request->all();
        $rules = [
            'id' => 'required|numeric',
            'nickname' => 'required',
            'aid' => 'required|numeric',
        ];
        $message = [
            'id.required' => 'id不能为空',
            'id.numeric' => 'id是数字类型',
            'nickname.required' => 'nickname不能为空',
            'aid.required' => 'aid不能为空',
            'aid.numeric' => 'aid是数字类型',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $find = User::getInstance()->findData(['id'=>$data['id']]);
        if(!$find) return fail('用户不存在');
        $arr = array();
        $arr['nickname'] = $data['nickname'];
        if(isset($data['password']) && $data['password']) $arr['password'] = md5($data['password'] . $find->salt);
        $arr['aid'] = $data['aid'];
        $arr['updatetime'] = time();
        User::getInstance()->editData(['id'=>$data['id']],$arr);
        return success($data);
    }
    /**
     * 页面管理用户删除
     */
    public function consumerDel(){
        $data = $this->request->all();
        $rules = [
            'id' => 'required|numeric',
        ];
        $message = [
            'id.required' => 'id不能为空',
            'id.numeric' => 'id是数字类型',
        ];
        $validator = make(ValidatorFactoryInterface::class)->make($data, $rules, $message);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return fail($msg[0]);
        }
        $del = User::getInstance()->delData(['id'=>$data['id']]);
        if(!$del) return fail('操作失败');
        return success($data);
    }

    /**
     * 用户信息
     */
    public function userInfo(){
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
        $info = User::query()->select('username','nickname')->where(['username'=>$data['username']])->first();
        if(!$info) return fail('该用户不存在');
        return success($info);
    }

    /**
     * 根据token获取用户信息
     */
    public function tokenInfo(){
        $data = $this->request->all();
        $getInfo = make(IndexController::class)->getToken($data['token']);
        $user = explode('|',$getInfo);
        $info = User::query()->select('username','nickname','aid')->where(['username'=>$user[0]])->first();
        if(!$info) return fail('该用户不存在');
        $info['role'] = Auth::getInstance()->findData(['id'=>$info->aid]);
        return success($info);
    }
}

