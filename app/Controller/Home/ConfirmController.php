<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Model\Balance;
use App\Model\Commodity;
use App\Model\Confirm;
use App\Model\Coupon;
use App\Model\Freight;
use App\Model\Member;
use App\Model\Order;
use App\Model\Repertory;
use App\Model\Running;
use App\Model\Suggest;
use App\Model\System;
use App\Model\Ticket;
use App\Model\Vehicle;
use App\Model\Warehouse;
use Hyperf\View\RenderInterface;
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
 * @ApiController(tag="前台确认订单", description="上班时间/余额/运费/优惠卷/确认订单")
 */

class ConfirmController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * User: liuan
     * Date: 2020/7/21 9:24
     * @PostApi(path="workday", description="上班时间")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":1,"begin：上班时间":"9:00","finish：下班时间":"21:00","phone：电话":"110","add_time":1594192435,"update":1594354222}})
     */
    public function workday(){
        $data = objectToArray(System::getInstance()->where('id',1)->first());

        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/7/21 10:00
     * @PostApi(path="balance", description="余额")
     * @Query(key="uid|用户id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"money：余额":"100.00"}})
     */
    public function balance(){
        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");

        $data = objectToArray(Member::getInstance()->where('id',$uid)->select('money')->first());
        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/8/10 11:21
     * @PostApi(path="getFreight", description="运费")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":1,"full：满":20,"subtract：减":10,"update":1597025950}})
     */
    public function getFreight(){
        $freight = objectToArray(Freight::getInstance()->where('id',1)->first());//运费满减
        return success($freight);
    }


    /**
     * User: liuan
     * Date: 2020/7/21 10:06
     * @PostApi(path="ticket", description="优惠券")
     * @Query(key="uid|用户id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":1,"name：名称":"优惠劵1","full：满多少":null,"not：减多少":"10.00","valid_time：有效时间":1596162015}})
     */
    public function ticket(){
        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");

        $data = Ticket::getInstance()->ticket($uid);

        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/8/22 9:06
     * @PostApi(path="confirmOrder", description="确认订单")
     * @Query(key="uid|用户id", rule="required")
     * @Query(key="site_id|地址id", rule="required")
     * @Query(key="discounts|优惠劵id", rule="required")
     * @Query(key="start|开始配送时间", rule="required")
     * @Query(key="end|配送结束时间", rule="required")
     * @Query(key="payment|1线上2线下", rule="required")
     * @Query(key="location|纬度:5.35882,经度:-3.96675", rule="required")
     * @Query(key="remark|备注", rule="")
     * @Query(key="text|建议", rule="")
     * @Query(key="goods|数组：goods_id:商品id,goods:商品名,number:数量}", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function confirmOrder(RenderInterface $render){
        $data = $this->request->all();

        if (!$data['uid']) return fail("L'ID utilisateur n'est pas renseigné");
        if (!$data['site_id']) return fail("L'identifiant de l'adresse n'est pas rempli");
//        if (!$data['discounts']) return fail('优惠劵id未填写');
        if (!$data['start']) return fail("Le délai de livraison de début n'est pas renseigné");
        if (!$data['end']) return fail("L'heure de fin de livraison n'est pas renseignée");
        if (!$data['payment']) return fail('Non renseigné en ligne et hors ligne');
        if (!$data['location']) return fail("L'emplacement n'est pas renseigné");

        $data['add_time'] = time();
        $data['status'] = 1;//提交订单

        $arr = '1234567890';
        $order = 'sxwm'.time().str_shuffle($arr);

        $data['order_number'] = $order;//订单号
        if (!$data['goods']) return fail('Produit non rempli');
        $goods = json_decode($data['goods'], true);

        //库存操作

//        $url = $data['location'];
//
//        $lists = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$url.'&key=AIzaSyBZxAbfgeDc2z6YUOaBs8b0NuQgm_cHLdw&language=pt-br';
//
//        $lists = PostRequest($lists,'');
//
//        $data['location'] = $lists['results'][0]['address_components'][1]['long_name'];
        $data['location'] = 'Cocody';

        $location = Warehouse::getInstance()->goodsList($data);

        if(empty($location['id'])){
            return fail('Aucun entrepôt dans la région! ! !');
        }

        foreach ($goods as $k => $v){
            $materials[] = Repertory::getInstance()->materials($v['goods_id']);
        }
        foreach ($goods as $k => $v){
            foreach ($materials as $a => $b){
                if($v['goods_id'] == $b['id']){
                   $info[$k]['nums'] = $b['num'] - $v['number'];
                   if($info[$k]['nums'] < 0){
                       return fail($v['goods']."Pénurie d'inventaire");
                   }
                   if($b['restrict'] < $v['number']){
                       return fail($v['goods']."Dépassez la limite d'achat");
                   }
                   Repertory::getInstance()->where('warehouse_id',$location['id'])->where('good_id',$v['goods_id'])->update(['num'=>$info[$k]['nums']]);
                   Running::getInstance()->insert(['good_id'=>$v['goods_id'],'warehouse_id'=>$location['id'],'num'=>$v['number'],'operation'=>'Commande, numéro de commande'.$order,'status'=>2,'add_time'=>time()]);
                }
            }
        }
        //结束库存操作

        $data['warehouse_id'] = $location['id'];//仓库id

        foreach ($goods as $k => $v){
            $new[$k]['goods_id'] = $v['goods_id'];//商品id
            $new[$k]['uid'] = $data['uid'];//用户id
            $new[$k]['goods'] = $v['goods'];//商品名
            $new[$k]['number'] = $v['number'];//数量
            $new[$k]['order'] = $order;//订单号
            $new[$k]['add_time'] = time();//时间
        }

        Order::getInstance()->insert($new);

        foreach ($goods as $k => $v){
            $list[] = Commodity::getInstance()->where('id',$v['goods_id'])
                ->sum("money") * $v['number'];
        }
        $money = array_sum($list);
        $data['price'] = $money;//订单金额

        if(!empty($data['discounts'])){
            $coupon = Coupon::getInstance()->discounts($data);
            if(!empty($coupon['full']) || !empty($couponp['not'])){
                if($data['price'] >= $coupon['full']){
                    $data['moneyNew'] = $data['price'] - $coupon['not'];
                }else{
                    return fail("Le coupon ne peut pas être utilisé et le montant de la réduction n'a pas été atteint");
                }
            }else{
                $data['moneyNew'] = $data['price'] - $coupon['not'];//优惠后金额
            }
            $data['coupon'] = $coupon['not'];
            Ticket::getInstance()->where('id',$coupon['id'])->update(['status'=>2,'update'=>time()]);
        }else{
            $data['moneyNew'] = $data['price'];
            $data['coupon'] = 0;
            unset($data['discounts']);
        }


        $balance = Member::getInstance()->balanceMoney($data);//用户账户金额

        if($balance['money'] >= $data['moneyNew']){
            $surplus['money'] = $balance['money'] - $data['moneyNew'];//用户剩余金额
            $data['money'] = 0;
            Balance::getInstance()->insert(['card'=>'Numéro de commande：'.$data['order_number'],'add_time'=>'temps de commande：'.date('Y-m-d h:i:s',time()),'money'=>$data['moneyNew'],'status'=>2,'uid'=>$data['uid']]);//余额流水
            Member::getInstance()->where('id',$data['uid'])->update(['money'=>$surplus['money']]);
        }else{
            $surplus['money'] = 0;
            $data['money'] = $data['moneyNew'] - $balance['money'];
            Balance::getInstance()->insert(['card'=>'Numéro de commande：'.$data['order_number'],'add_time'=>'temps de commande：'.date('Y-m-d h:i:s',time()),'money'=>$balance['money'],'status'=>2,'uid'=>$data['uid']]);
            Member::getInstance()->where('id',$data['uid'])->update(['money'=>$surplus['money']]);
        }

        $user = objectToArray(Member::getInstance()->where('id',$data['uid'])->first());

        if(!empty($data['text'])){
            Suggest::getInstance()->insert(['name'=>$user['username'],'phone'=>$user['phone'],'goods'=>$data['text'],'add_time'=>time()]);//建议添加
        }
        unset($data['text']);

        $freight = objectToArray(Freight::getInstance()->where('id',1)->first());//运费满减
        if($money < $freight['full']){
            $data['freight'] = $freight['subtract'];
        }else{
            $data['freight'] = 0;
        }

        $data['money'] = $data['money'] + $freight['freight'];

        //金额为0的时候
        if($data['money'] == 0){
            $data['pay_time'] = time();
            $data['status'] = 3;//已支付订单

            unset($data['goods']);
            unset($data['location']);
            unset($data['moneyNew']);

            $list = Confirm::getInstance()->insert($data);

            foreach ($new as $k => $v){
                Vehicle::getInstance()->where('goods_id',$v['goods_id'])->delete();
            }
            if(!empty($list)){
                return success('paiement réussi');
            }else{
                return fail('Paiement échoué');
            }
        }

        //不为0的时候支付
        if($data['payment'] == 1){
            //线上支付
            if($data['money'] < 100){
                $data['money'] = 100;//支付金额
            }
            if(empty($data['remark'])){
                $data['remark'] = 'Frais à emporter';//购买描述
            }

            unset($data['goods']);
            unset($data['location']);
            unset($data['moneyNew']);

            Confirm::getInstance()->insert($data);
            foreach ($new as $k => $v){
                Vehicle::getInstance()->where('goods_id',$v['goods_id'])->delete();
            }
            return $render->render('paymentTest',$data);

        }else{
            //线下支付
            $data['pay_time'] = time();
            $data['status'] = 3;//已支付订单

            unset($data['goods']);
            unset($data['location']);
            unset($data['moneyNew']);

            $list = Confirm::getInstance()->insert($data);

            foreach ($new as $k => $v){
                Vehicle::getInstance()->where('goods_id',$v['goods_id'])->delete();
            }
            if(!empty($list)){
                return success('paiement réussi');
            }else{
                return fail('Paiement échoué');
            }
        }


    }



    /**
     * User: liuan
     * Date: 2020/7/30 9:48
     * 支付回调
     */
    public function notify(ResponseInterface $response){
        $all = $this->request->all();

        var_dump($all,__LINE__);
        if($all['cpm_result'] == 00){
            $time = strtotime($all['cpm_trans_date']);
            Confirm::getInstance()->where('order_number',$all['cpm_trans_id'])->update(['status'=>3,'pay_time'=>$time]);
            var_dump('支付回调',__LINE__);
            return $response->redirect('http://www.baidu.com');
        }else{
            return '';
        }

    }


    public function addfff(RenderInterface $render){
        $data['add'] = 1111;
        $data['list'] = 2222;
        $data['user'] = 333;
//        return $render->render('index',$data)->withHeader("aaa","333333");
    }





}
