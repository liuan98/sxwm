<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Model\Confirm;
use App\Model\Evaluate;
use App\Model\Member;
use App\Model\Order;
use App\Model\Site;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\ApiResponse;
use Hyperf\Apidog\Annotation\Body;
use Hyperf\Apidog\Annotation\DeleteApi;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Apidog\Annotation\GetApi;
use Hyperf\Apidog\Annotation\Header;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Apidog\Annotation\Query;
use Hyperf\View\Render;
use Hyperf\View\RenderInterface;

/**
 * @ApiController(tag="前台订单", description="订单列表/待评价/订单删除/订单评价/订单详情/退款/待支付/24小时后自动收货/申请或取消退款/确认收货")
 */
class OrderListController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * User: liuan
     * Date: 2020/7/22 16:26
     * @PostApi(path="getOrder", description="订单列表")
     * @Query(key="uid|用户id", rule="required")
     * @Query(key="status|1提交订单2未支付3已支付4配送中5确认收货6退货中7退货成功8退货失败9取消退货", rule="")
     * @Query(key="id|分页", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"order_number：订单号":"sxwm15954821684126983057","list":{"id":3,"order：订单号":"sxwm15953327116753910482","name：名称":"幼儿|棉质裤子","img：图片":"1.jpg","money：价格":"10.00","standard：规格":"","number：数量":2,"status：1提交订单2未支付3已支付4配送中5确认收货6退货中7退货成功8退货失败9取消退货":3}}})
     */
    public function getOrder(){
        $id = max(intval($this->request->input('id')), 0);
        $id = $id && $id > 0 ? $id : 0;
        $where[] = $id > 0 ? ['id', '<', $id] : ['id', '>', 0];

        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");

        $status = $this->request->input('status');

        if(empty($status)){
            $data = Confirm::getInstance()->Order($uid,$where);
        }else{
            $data = Confirm::getInstance()->getOrder($uid,$status,$where);
        }

        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/7/22 17:39
     * @PostApi(path="getStay", description="待评价")
     * @Query(key="uid|用户id", rule="required")
     * @Query(key="id|分页", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"order_number：订单号":"sxwm15953327116753910482","list":{"id":3,"order：订单号":"sxwm15953327116753910482","name：名称":"幼儿|棉质裤子","img：图片":"1.jpg","money：价格":"10.00","standard：规格":"","number：数量":2,"evaluate：评价1未评价":3}}})
     */
    public function getStay(){
        $id = max(intval($this->request->input('id')), 0);
        $id = $id && $id > 0 ? $id : 0;
        $where[] = $id > 0 ? ['id', '<', $id] : ['id', '>', 0];

        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");

        $data = Confirm::getInstance()->getStay($where,$uid);

        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/7/22 18:19
     * @PostApi(path="StayDelete", description="订单删除")
     * @Query(key="id|订单id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function StayDelete(){
        $id = $this->request->input('id');
        if (!$id) return fail("L'ID de commande n'est pas rempli");

        $list = Confirm::getInstance()->where('order_number',$id)->delete();
        if(!empty($list)){
            return success("supprimé avec succès");
        }else{
            return fail('échec de la suppression');
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/22 18:24
     * @PostApi(path="orderStay", description="订单评价")
     * @Query(key="uid|用户id", rule="required")
     * @Query(key="serial|订单号", rule="required")
     * @Query(key="text|内容", rule="required")
     * @Query(key="level|星级", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function orderStay(){
        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");

        $user = objectToArray(Member::getInstance()->where('id',$uid)->first());

        $data['name'] = $user['username'];
        $data['phone'] = $user['phone'];

        $serial = $this->request->input('serial');
        if (!$serial) return fail("Le numéro de commande n'est pas renseigné");
        $data['text'] = $this->request->input('text');
        if (!$data['text']) return fail("Le contenu n'est pas rempli");
        $data['level'] = $this->request->input('level');
        if (!$data['level']) return fail("Le nombre d'étoiles n'est pas rempli");

        $goods = Order::getInstance()->where('order',$serial)->get();

        foreach ($goods as $k => $v){
            $info[$k]['goods_id'] = $v['goods_id'];
            $info[$k]['name'] = $data['name'];
            $info[$k]['phone'] = $data['phone'];
            $info[$k]['serial'] = $serial;
            $info[$k]['text'] = $data['text'];
            $info[$k]['level'] = $data['level'];
            $info[$k]['add_time'] = time();
        }

        $list = Evaluate::getInstance()->insert($info);
        if(!empty($list)){
            return success('Évaluation réussie');
        }else{
            return fail("L'évaluation a échoué");
        }
    }


    /**
     * User: liuan
     * Date: 2020/7/22 18:01
     * @PostApi(path="stayList", description="订单详情")
     * @Query(key="order_number|订单号", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"goods：商品":{"name：名称":"幼儿|棉质裤子","img：图片":"1.jpg","standard：规格":"","money：单价":"10.00","number：数量":2},"site":{"id":1,"uid":1,"name：名称":"老王","phone：电话":"110","site：地址":"江西南昌","detail：详细地址":"红谷滩万达b1 1313","label：标签":"公司","status":2,"add_time":1595235466,"update":1595238729},"payment：1线上2线下":"1","evaluate：1未评价2已评价":"1","remark：备注":"雷大哥你别打我，这个东西很完美","price：商品总价":"30.00","coupon：优惠劵金额":10,"money：支付金额":"0.00","balance：余额支付":20,"time：上门时间":"预计2020-07-21 10:20:15~2020-07-21 06:40:17","order_number：订单编号":"sxwm15953327116753910482","add_time：下单时间":1595332711,"pay_time：支付时间":1595332711,"deliver_time：退货时间":1595332711,"complete_time：确认时间":1595332711,"sales_time：确认时间":1595332711,"status：1提交订单2未支付3已支付4配送中5确认收货6退货中7退货成功8退货失败9取消退货":5}})
     */
    public function stayList(){
        $order_number = $this->request->input('order_number');
        if (!$order_number) return fail("Le numéro de commande n'est pas renseigné");

        $data['goods'] = Order::getInstance()->stayList($order_number);

        $pay = objectToArray(Confirm::getInstance()->where('order_number',$order_number)->first());

        $data['site'] = objectToArray(Site::getInstance()->where('id',$pay['site_id'])->first());

        $data['remark'] = $pay['remark'];//备注

        $data['price'] = $pay['price'];//商品总价

        $data['freight'] = $pay['freight'];//运费

        $data['coupon'] = $pay['coupon'];//优惠劵金额

        $data['money'] = $pay['money'];//支付金额

        $data['payment'] = $pay['payment'];//1线上2线下

        $data['balance'] = $pay['price'] - $pay['coupon'] - $pay['money'];//余额支付

        $data['time'] = '预计'.date('Y-m-d h:i:s',$pay['start']).'~'.date('Y-m-d h:i:s',$pay['end']);//上门时间

        $data['order_number'] = $pay['order_number'];//订单编号

        $data['add_time'] = $pay['add_time'];//下单时间

        $data['pay_time'] = $pay['pay_time'];//支付时间

        $data['status'] = $pay['status'];//1提交订单2未支付3已支付4配送中5确认收货6退货中7退货成功8退货失败9取消退货

        if($data['status'] == 5){
            $data['evaluate'] = $pay['evaluate'];//1未评价2已评价
        }

        if($pay['payment'] == 2){
            unset($data['pay_time']);
        }

        if($pay['status'] == 4){
            $data['deliver_time'] = $pay['deliver_time'];//发货时间
        }

        if($pay['status'] == 5){
            $data['complete_time'] = $pay['complete_time'];//确认时间
        }

        if($pay['status'] == 9){
            $data['sales_time'] = $pay['sales_time'];//退货时间
            unset($data['deliver_time']);
            unset($data['complete_time']);
        }

        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/7/22 19:59
     * @PostApi(path="salesPay", description="退款")
     * @Query(key="order_number|订单号", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function salesPay(){
        $order_number = $this->request->input('order_number');
        if (!$order_number) return fail("Le numéro de commande n'est pas renseigné");

        $data = Confirm::getInstance()->where('order_number',$order_number)->update(['status'=>6]);

        if(!empty($data)){
            return success('Succès');
        }else{
            return fail('échec');
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/22 20:07
     * @PostApi(path="stayPay", description="待支付")
     * @Query(key="order_number|订单号", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function stayPay(RenderInterface $render){
        $order_number = $this->request->input('order_number');
        if (!$order_number) return fail("Le numéro de commande n'est pas renseigné");

        $data = objectToArray(Confirm::getInstance()->where('order_number',$order_number)->first());

        return $render->render('paymentTest',$data);
    }

    /**
     * User: liuan
     * Date: 2020/7/22 20:12
     * @PostApi(path="voluntarily", description="24小时后自动收货")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function voluntarily(){
        $time = time() - 86400;

        Confirm::getInstance()->where('deliver_time','<',$time)->update(['status'=>5,'complete_time'=>time()]);
        return success('');
    }

    /**
     * User: liuan
     * Date: 2020/7/27 11:45
     * @PostApi(path="confirmReceipt", description="确认收货")
     * @Query(key="order_number|订单号", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function confirmReceipt(){
        $order_number = $this->request->input('order_number');
        if (!$order_number) return fail("Le numéro de commande n'est pas renseigné");

        $data = Confirm::getInstance()->where('order_number',$order_number)->update(['status'=>5]);

        if(!empty($data)){
            return success('Succès');
        }else{
            return fail('échec');
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/22 21:01
     * @PostApi(path="applyPay", description="申请或取消退款")
     * @Query(key="order_number|订单号", rule="required")
     * @Query(key="status|1申请退款2取消", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function applyPay(){
        $order_number = $this->request->input('order_number');
        if (!$order_number) return fail("Le numéro de commande n'est pas renseigné");

        $list = Confirm::getInstance()->where('order_number',$order_number)->value('status');
        if($list == 9){
            return fail('Aucune demande de remboursement');
        }

        $status = $this->request->input('status');
        if($status == 1){
            $status = 6;
        }else{
            $status = 9;
        }

        $data = Confirm::getInstance()->where('order_number',$order_number)->update(['status'=>$status]);

        if(!empty($data)){
            return success('Succès');
        }else{
            return fail('échec');
        }
    }


}
