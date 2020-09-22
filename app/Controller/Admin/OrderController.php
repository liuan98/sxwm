<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Balance;
use App\Model\Commodity;
use App\Model\Confirm;
use App\Model\Member;
use App\Model\Order;
use App\Model\Repertory;
use App\Model\Running;
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
/**
 * @ApiController(tag="后台订单管理", description="订单列表/发货完成按钮/取消订单跟线上取消退款退款可共用/取消退款/删除订单/订单详情")
 */
class OrderController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * User: liuan
     * Date: 2020/7/24 10:20
     * @PostApi(path="orderList", description="订单列表")
     * @Query(key="status|3待发货4已发货5已完成7已取消", rule="")
     * @Query(key="id|分页", rule="")
     * @Query(key="name|名称", rule="")
     * @Query(key="order_number|订单号", rule="")
     * @Query(key="time|下单时间戳", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":{"id":4,"order_number：订单号":"sxwm15954821684126983057","name：名称":"老王","phone：电话":"110","money：金额":"0.00","payment：1线上2线下":2,"start：送货开始":1595298015,"end：送货结束":1595328017,"add_time":1595482168,"status：1提交订单2未支付3已支付4配送中5确认收货6退货中7退货成功8退货失败9取消退货":3,"number：数量":"3"}})
     */
    public function orderList(){
        $status = $this->request->input("status");

        $id = max(intval($this->request->input('id')), 0);
        $order_number = $this->request->input("order_number");
        $name = $this->request->input("name");
        $id = $id && $id > 0 ? $id : 0;
        $where[] = $id > 0 ? ['p.id', '<', $id] : ['p.id', '>', 0];
        if(!empty($order_number)){
            $where[] = ['p.order_number', 'like', '%'.$order_number.'%'];
        }else{
            $where[] = ['s.name', 'like', '%'.$name.'%'];
        }

        $time = intval($this->request->input('time'));

        $data = Confirm::getInstance()->getAll($where,$status,$time);

        return success($data);
    }


    /**
     * User: liuan
     * Date: 2020/7/24 14:30
     * @PostApi(path="orderCancel", description="发货完成按钮")
     * @Query(key="id|订单id", rule="required")
     * @Query(key="status|4发货5已完成", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function orderCancel(){
        $id = $this->request->input('id');
        if (!$id) return fail("L'ID de commande n'est pas rempli");

        $status = $this->request->input('status');
        if (!$status) return fail("Le statut n'est pas rempli");
        $data = Confirm::getInstance()->where('id',$id)->update(['status'=>$status]);

        if(!empty($data)){
            return success('Succès');
        }else{
            return fail('échec');
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/24 14:49
     * @PostApi(path="getCancel", description="取消订单跟线上同意退款可共用")
     * @Query(key="id|订单id", rule="required")
     * @Query(key="order_number|订单号", rule="required")
     * @Query(key="payment|1线上2线下", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function getCancel(){
        $id = $this->request->input('id');
        if (!$id) return fail("L'ID de commande n'est pas rempli");

        $order_number = $this->request->input('order_number');
        if (!$order_number) return fail("L'ID de commande n'est pas rempli");

        $payment = $this->request->input('payment');
        if (!$payment) return fail("Non renseigné en ligne et hors ligne");

        //线上
        if($payment == 1){

            $card = 'Numéro de commande：'.$order_number;
            $repertory = 'Commande, numéro de commande'.$order_number;

            //金额退回
            $money = objectToArray(Balance::getInstance()->where('card', 'like', '%'.$card.'%')->first());
            $user = objectToArray(Member::getInstance()->where('id',$money['uid'])->first());
//            $price = $user['money'] + $money['money'];
            Balance::getInstance()->insert(['uid'=>$user['id'],'card'=>'Numéro de commande de remboursement：'.$order_number,'add_time'=>'Remboursement en ligne, délai de remboursement'.date('Y-m-s h:i:s',time()),'money'=>$money['money'],'status'=>1]);
//            Member::getInstance()->where('id',$user['id'])->update(['money'=>$price]);

            //库存退回
            $num = Running::getInstance()->where('operation', 'like', '%'.$repertory.'%')->get();
            foreach ($num as $k => $v){
                $warehouse[] = objectToArray(Repertory::getInstance()->where('good_id',$v['good_id'])->where('warehouse_id',$v['warehouse_id'])->first());
                foreach ($warehouse as $a => $b){
                    $new[$a]['num'] = $v['num'] + $b['num'];
                    $new[$a]['id'] = $b['id'];
                }
                Running::getInstance()->insert(['good_id'=>$v['good_id'],'warehouse_id'=>$v['warehouse_id'],'num'=>$v['num'],'operation'=>'Numéro de commande de retour'.$order_number,'status'=>1,'add_time'=>time()]);
            }

            foreach ($new as $k => $v){
                Repertory::getInstance()->where('id',$v['id'])->update(['num'=>$v['num']]);
            }


            $data = Confirm::getInstance()->where('id',$id)->update(['status'=>7]);

            if(!empty($data)){
                return success('Succès');
            }else{
                return fail('échec');
            }
        }

        $card = 'Numéro de commande：'.$order_number;
        $repertory = 'Commande, numéro de commande'.$order_number;

        //金额退回
        $money = objectToArray(Balance::getInstance()->where('card', 'like', '%'.$card.'%')->first());
        $user = objectToArray(Member::getInstance()->where('id',$money['uid'])->first());
        $price = $user['money'] + $money['money'];
        Balance::getInstance()->insert(['uid'=>$user['id'],'card'=>'Numéro de commande de remboursement：'.$order_number,'add_time'=>'Remboursement hors ligne, délai de remboursement'.date('Y-m-s h:i:s',time()),'money'=>$money['money'],'status'=>1]);
        Member::getInstance()->where('id',$user['id'])->update(['money'=>$price]);

        //库存退回
        $num = Running::getInstance()->where('operation', 'like', '%'.$repertory.'%')->get();
        foreach ($num as $k => $v){
            $warehouse[] = objectToArray(Repertory::getInstance()->where('good_id',$v['good_id'])->where('warehouse_id',$v['warehouse_id'])->first());
            foreach ($warehouse as $a => $b){
                $new[$a]['num'] = $v['num'] + $b['num'];
                $new[$a]['id'] = $b['id'];
            }
            Running::getInstance()->insert(['good_id'=>$v['good_id'],'warehouse_id'=>$v['warehouse_id'],'num'=>$v['num'],'operation'=>'Numéro de commande de retour'.$order_number,'status'=>1,'add_time'=>time()]);
        }

        foreach ($new as $k => $v){
            Repertory::getInstance()->where('id',$v['id'])->update(['num'=>$v['num']]);
        }

        $data = Confirm::getInstance()->where('id',$id)->update(['status'=>7]);

        if(!empty($data)){
            return success('Succès');
        }else{
            return fail('échec');
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/24 14:43
     * @PostApi(path="getRefund", description="取消退款")
     * @Query(key="id|订单id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function getRefund(){
        $id = $this->request->input('id');
        if (!$id) return fail("L'ID de commande n'est pas rempli");
        $data = Confirm::getInstance()->where('id',$id)->update(['status'=>8]);

        if(!empty($data)){
            return success('Succès');
        }else{
            return fail('échec');
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/24 16:55
     * @PostApi(path="orderDelete", description="删除订单")
     * @Query(key="id|订单id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function orderDelete(){
        $id = $this->request->input('id');
        if (!$id) return fail("L'ID de commande n'est pas rempli");

        $data = Confirm::getInstance()->where('id',$id)->delete();
        if(!empty($data)){
            return success('supprimé avec succès');
        }else{
            return fail('échec de la suppression');
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/24 17:00
     * @PostApi(path="orderDetails", description="订单详情")
     * @Query(key="id|订单id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"list：买家信息":{"id":4,"order_number：订单号":"sxwm15954821684126983057","name：用户名":"老王","phone：电话":"110","site：地址":"江西南昌","detail：详细地址":"红谷滩万达b1 1313","payment：1线上2线下":2,"start：上门开始时间":1595298015,"end：上门结束时间":1595328017,"status":3,"add_time：下单时间":1595482168,"pay_time：支付时间":1595482168,"deliver_time：发货时间":null,"complete_time：完成时间":null,"remark：备注":"4234"},"order：订单":{"id":14,"name：商品名":"幼儿|棉质裤子","img：图片":"1.jpg","standard：规格":"","number：数量":2,"money：金额":"10.00","price：单个商品总金额":"20.00"},"discounts：优惠金额":10,"balance：余额":20,"deal应付":"0.00"}})
     */
    public function orderDetails(){
        $id = $this->request->input('id');
        if (!$id) return fail("L'ID de commande n'est pas rempli");

        $pay = objectToArray(Confirm::getInstance()->where('id',$id)->first());

        $data['list'] = Confirm::getInstance()->orderDetails($id);//买家信息

        $data['order'] = Order::getInstance()->orderNew($pay);//商品

        $data['discounts'] = $pay['coupon'];//优惠金额

        $data['balance'] = $pay['price'] - $pay['coupon'];//账号余额

        $data['deal'] = $pay['money'];//应付

        return success($data);
    }


}
