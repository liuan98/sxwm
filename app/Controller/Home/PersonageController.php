<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Model\Confirm;
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
 * @ApiController(tag="前台我的", description="我的首页/个人详情/修改/修改电话")
 */


class PersonageController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * User: liuan
     * Date: 2020/7/21 20:14
     * @PostApi(path="myList", description="我的首页")
     * @Query(key="uid|用户id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"user":{"id":1,"username：用户名":"客户1","money：余额":"60.00","img：头像":null},"send：待发货":3,"present：待收货":0,"finish：已完成":0,"evaluate：待评价":0,"coupon：优惠劵":1}})
     */
    public function myList(){
        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");

        $data['user'] = objectToArray(Member::getInstance()->where('id',$uid)->select('id','username','money','img')->first());

        $data['send'] = Confirm::getInstance()->where('uid',$uid)->where('status',3)->count();//待发货

        $data['present'] = Confirm::getInstance()->where('uid',$uid)->where('status',4)->count();//待收货

        $data['finish'] = Confirm::getInstance()->where('uid',$uid)->where('status',5)->count();//已完成

        $data['evaluate'] = Confirm::getInstance()->where('uid',$uid)->where('status',5)->where('evaluate',1)->count();//待评价

        $data['coupon'] = Ticket::getInstance()->where('uid',$uid)->where('status',1)->where('valid_time','>',time())->count();//优惠劵
        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/7/21 20:55
     * @PostApi(path="getMy", description="个人详情")
     * @Query(key="uid|用户id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":1,"username：名称":"客户1","phone：电话":"110","img：头像":null,"mail：邮箱":null}})
     */
    public function getMy(){
        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");

        $data = objectToArray(Member::getInstance()->where('id',$uid)->select('id','username','phone','img','mail')->first());

        return success($data);
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/21 21:05
     * @PostApi(path="myUpdate", description="修改个人")
     * @Query(key="uid|用户id", rule="required")
     * @Query(key="username|名称", rule="")
     * @Query(key="img|头像", rule="")
     * @Query(key="mail|邮箱", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function myUpdate(){
        $data = $this->request->all();

        if (!$data['uid']) return fail("L'ID utilisateur n'est pas renseigné");

        $data['update'] = time();
        $id = $data['uid'];
        unset($data['uid']);

        $list = Member::getInstance()->where('id',$id)->update($data);
        if(!empty($list)){
            return success('Modifié avec succès');
        }else{
            return fail('échec de modifier');
        }
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/22 9:08
     * @PostApi(path="myPhone", description="修改电话")
     * @Query(key="uid|用户id", rule="required")
     * @Query(key="telephone|原电话", rule="required")
     * @Query(key="phone|电话", rule="required")
     * @Query(key="verify|原电话验证码", rule="required")
     * @Query(key="verifyTwo|验证码", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function myPhone(){
        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");

        $verify = $this->request->input("verify");
        if (!$verify) return fail("Le code de vérification d'origine du téléphone n'est pas renseigné");
        $telephone = $this->request->input('telephone');
        if (!$telephone) return fail("Le numéro de téléphone d'origine n'est pas rempli");
        $redis = $this->container->get(\Hyperf\Redis\Redis::class);
        $code = $redis->get('code'.$telephone);
        if($code != $verify) return fail("Le code de vérification d'origine du téléphone est incorrect");

        $verifyTwo = $this->request->input("verifyTwo");
        if (!$verifyTwo) return fail("Le code de vérification n'est pas renseigné");
        $data['phone'] = $this->request->input('phone');
        $redis = $this->container->get(\Hyperf\Redis\Redis::class);
        $code = $redis->get('code'.$data['phone']);
        if($code != $verifyTwo) return fail("Erreur de code de vérification");

        $list = Member::getInstance()->where('id',$uid)->where('phone',$telephone)->update($data);
        if(!empty($list)){
            return success('Modifié avec succès');
        }else{
            return fail('échec de modifier');
        }
    }


}
