<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Model\Confirm;
use App\Model\Count;
use App\Model\Coupon;
use App\Model\Member;
use App\Model\Ticket;
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
 * @ApiController(tag="前台优惠劵", description="个人优惠劵/领劵中心/领劵中心领取优惠劵")
 */
class GetCouponController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * User: liuan
     * Date: 2020/7/23 13:58
     * @PostApi(path="userCoupon", description="个人优惠劵")
     * @Query(key="uid|用户id", rule="required")
     * @Query(key="status|默认有效1使用过2过期的", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":3,"name：名称":"优惠劵3","full：满多少":null,"not：减":"10.00","valid_time：有效时间":1595211434}})
     */
    public function userCoupon(){
        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");

        $status = $this->request->input('status');

        if(empty($status)){
            $data = Ticket::getInstance()->ticket($uid);
            foreach ($data as $key=>$val){
                $getOrderCoupon =  Confirm::query()->where(['discounts'=>$val['id']])->first();
                if($getOrderCoupon){
                    unset($data[$key]);
                }
            }
            $data = array_values($data);
        }elseif ($status == 1){
            $data = Ticket::getInstance()->userCoupon($uid);
        }elseif ($status == 2){
            $data = Ticket::getInstance()->listCoupon($uid);
        }
        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/7/23 14:32
     * @PostApi(path="centreCoupon", description="领劵中心")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":8,"name：名称":"优惠劵3","way：发放方式":"领劵中心领取","money：金额":"10.00","count：数量":"无限制","restrict：限制每人几张":1,"end：有效时间":"有效期30天","add_time：添加时间":"2020-07-17 06:27:03"}})
     */
    public function centreCoupon(){
        $coupon = Coupon::getInstance()->centreCoupon();

        $num = count($coupon) - 1;

        for ($i=0; $i<=$num; $i++)
        {
            $listApp[$i]['id'] = $coupon[$i]['id'];
            $listApp[$i]['name'] = $coupon[$i]['name'];
            $listApp[$i]['full'] = $coupon[$i]['full'];
            $listApp[$i]['not'] = $coupon[$i]['not'];
            $listApp[$i]['start'] = $coupon[$i]['start'];
            $listApp[$i]['end'] = $coupon[$i]['end'];
            $listApp[$i]['day'] = $coupon[$i]['day'];
            $listApp[$i]['way'] = $coupon[$i]['way'];
            $listApp[$i]['restrict'] = $coupon[$i]['restrict'];
            $listApp[$i]['count'] = $coupon[$i]['count'];
            $listApp[$i]['add_time'] = $coupon[$i]['add_time'];
            $listApp[$i]['update'] = $coupon[$i]['update'];
            if($listApp[$i]['end'] < time() && $listApp[$i]['end'] >1){
                unset($listApp[$i]);
            }
        }
        foreach ($listApp as $k => $v){
            $newList[$k]['id'] = $v['id'];
            $newList[$k]['name'] = $v['name'];
            $newList[$k]['way'] = $v['way'] == 0?'Recevoir un centre de bons':'inviter'.$v['way']."Réclamation d'amis";
            $newList[$k]['money'] = empty($v['full'])?$v['not']:$v['full'].'-'.$v['not'];
            $newList[$k]['count'] = $v['count'] == 999999999?'Illimité':$v['count'];
            $newList[$k]['restrict'] = $v['restrict'];
            $newList[$k]['end'] = empty($v['end'])?'Période de validité'.$v['day'].'journée':date('Y-m-d h:i:s',$v['start']).'à'.date('Y-m-d h:i:s',$v['end']);
            $newList[$k]['add_time'] = date('Y-m-d h:i:s',$v['add_time']);
        }

        return success($newList);
    }

    /**
     * User: liuan
     * Date: 2020/7/23 14:42
     * @PostApi(path="getNeck", description="领劵中心领取优惠劵")
     * @Query(key="uid|用户id", rule="required")
     * @Query(key="coupon_id|优惠劵id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function getNeck(){
        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");
        $coupon_id = $this->request->input('coupon_id');
        if (!$coupon_id) return fail("L'identifiant du coupon n'est pas renseigné");

        $coupon = objectToArray(Coupon::getInstance()->where('id',$coupon_id)->first());

        if($coupon['count'] < 1){
            return fail('Le coupon a été émis');
        }

        $numder = Ticket::getInstance()->where('uid',$uid)->where('coupon_id',$coupon_id)->count();

        if($numder >= $coupon['restrict']){
            return fail('Vous avez atteint la limite de réclamation');
        }

        $data['uid'] = $uid;
        $data['coupon_id'] = $coupon_id;
        $data['add_time'] = time();
        if(empty($coupon['end'])){
            $data['valid_time'] = time() + (86400 * $coupon['day']);
        }else{
            $data['valid_time'] = $coupon['end'];
        }

        if($coupon['count'] !== 999999999){
            $count = $coupon['count'] - 1;
            Coupon::getInstance()->where('id',$coupon['id'])->update(['count'=>$count]);
        }

        $list = Ticket::getInstance()->insert($data);
        if(!empty($list)){
            return success('Ajouté avec succès');
        }else{
            return fail('ajouter a échoué');
        }
    }


}
