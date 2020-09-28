<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Model\Count;
use App\Model\Coupon;
use App\Model\Invite;
use App\Model\Member;
use App\Model\Ticket;
use EasyWeChat\BasicService\QrCode\Client;
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
use Endroid\QrCode\QrCode;
use Hyperf\Validation\Rules\In;

/**
 * @ApiController(tag="前台分享app", description="分享app首页/领取优惠劵/邀请注册/下载二维码")
 */

class WuploadController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * User: liuan
     * Date: 2020/7/22 10:52
     * @PostApi(path="codeList", description="分享app首页")
     * @Query(key="uid|用户id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"list：活动介绍":"sjbgsogogkjsgkskjogsoigbkjsogosoghhs","img：二维码":"\/img\/1595392366.png","url：链接":"www.baidu.com","num：人数":1,"coupon":{"id":10,"name：优惠劵名":"优惠劵3","way：发放方式":0,"money：面额":"10.00","count：数量":0,"restrict：每人可以领多少":0,"end：有效时间":"2020-07-16 12:00:07至2020-07-20 12:00:07","add_time：创建时间":"2020-07-17 06:27:03"}}})
     */
    public function codeList(){
        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");

        $data['list'] = objectToArray(Invite::getInstance()->value('text'));

        $user = objectToArray(Member::getInstance()->where('id',$uid)->first());

        if(empty($user['code'])){
            $content = 'http://www.bellavieci.com/h5/index.html?uid='.$user['id'];
            $qrCode = new QrCode($content);

            header('Content-Type: '.$qrCode->getContentType());
            // 输出二维码
            $url = 'public/img/'.time().'.png';
            $qrCode->writeFile($url);
            $new = substr($url,strpos($url,'/'));
            $img = $_SERVER['HTTP_HOST'].$new;//二维码路径

            Member::getInstance()->where('id',$uid)->update(['code'=>$img]);
        }
        $list = objectToArray(Member::getInstance()->where('id',$uid)->first());
        $data['img'] = $list['code'];//二维码

        $data['url'] = 'http://www.bellavieci.com/h5/index.html?uid='.$user['id'];//链接

        $data['num'] = Count::getInstance()->codeNum($user);

        $coupon = Coupon::getInstance()->couponCode();

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
            $newList[$k]['count'] = $v['count'] == 999999999?'无限制':$v['count'];
            $newList[$k]['restrict'] = $v['restrict'];
            $newList[$k]['end'] = empty($v['end'])?'Période de validité'.$v['day'].'journée':date('Y-m-d h:i:s',$v['start']).'à'.date('Y-m-d h:i:s',$v['end']);
            $newList[$k]['add_time'] = date('Y-m-d h:i:s',$v['add_time']);
        }

        $data['coupon'] = $newList;

        return success($data);

    }


    /**
     * User: liuan
     * Date: 2020/7/22 15:01
     * @PostApi(path="getCoupon", description="领取优惠劵")
     * @Query(key="uid|用户id", rule="required")
     * @Query(key="coupon_id|优惠劵id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function getCoupon(){
        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");
        $coupon_id = $this->request->input('coupon_id');
        if (!$coupon_id) return fail("L'identifiant du coupon n'est pas renseigné");

        $user = objectToArray(Member::getInstance()->where('id',$uid)->first());

        $num = Count::getInstance()->codeNum($user);

        $coupon = objectToArray(Coupon::getInstance()->where('id',$coupon_id)->first());

        if($coupon['count'] < 1){
            return fail('Le coupon a été émis');
        }

        if($coupon['way'] > $num){
            return fail('Ne répondait pas aux exigences');
        }
        $numder = Ticket::getInstance()->where('uid',$uid)->where('coupon_id',$coupon_id)->count();

        if($numder >= $coupon['restrict']){
            return fail('Vous avez atteint la limite de réclamati');
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

    /**
     * User: liuan
     * Date: 2020/7/22 15:51
     * @PostApi(path="getRegister", description="邀请注册")
     * @Query(key="uid|被分享人id", rule="required")
     * @Query(key="phone|电话", rule="required")
     * @Query(key="mail|邮箱", rule="")
     * @Query(key="verify|验证码", rule="required")
     * @Query(key="password|密码", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function getRegister(){
        $new['uid'] = $this->request->input("uid");
        if (!$new['uid']) return fail('uid');
        $new['add_time'] = time();

        Count::getInstance()->insert($new);

        $data['phone'] = $this->request->input("phone");
        if (!$data['phone']) return fail("Le téléphone n'est pas rempli");
        $info = objectToArray(Member::getInstance()->where('phone',$data['phone'])->first());
        if(!empty($info)){
            return fail('Ce téléphone est déjà enregistré');
        }

        $data['mail'] = $this->request->input("mail");

        $password = $this->request->input("password");
        if (!$password) return fail("Le mot de passe n'est pas rempli");
        $data['password'] = md5($password);

        $verify = $this->request->input("verify");
        if (!$verify) return fail("Le code de vérification n'est pas renseigné");
        $redis = $this->container->get(\Hyperf\Redis\Redis::class);
        $code = $redis->get('code'.$data['phone']);
        if($code != $verify) return fail("Erreur de code de vérification");

        $arr = '1234567890';
        $data['username'] = 'utilisateur'.str_shuffle($arr);
        $data['add_time'] = time();

        $list = Member::getInstance()->insert($data);
        if(!empty($list)){
            return success('Ajouté avec succès');
        }else{
            return fail('ajouter a échoué');
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/22 16:11
     * @PostApi(path="getCode", description="下载二维码")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"android：安卓":"www.jd.com","ios：苹果":"www.taobao.com"}})
     */
    public function getCode(){
        $data['android'] = 'http://www.bellavieci.com';

        $data['ios'] = 'http://www.bellavieci.com';

        return success($data);
    }


}
