<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Model\About;
use App\Model\Balance;
use App\Model\Member;
use App\Model\Shopping;
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
use Hyperf\DbConnection\Db;

/**
 * @ApiController(tag="前台礼品卡兑换与账户余额", description="礼品卡兑换/礼品卡规则/账户余额")
 */
class BalanceController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * User: liuan
     * Date: 2020/7/23 10:44
     * @PostApi(path="getCard", description="礼品卡兑换")
     * @Query(key="uid|用户id", rule="required")
     * @Query(key="card|卡号", rule="required")
     * @Query(key="password|密码", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function getCard(){
        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");

        $card = $this->request->input('card');
        if (!$card) return fail("Le contenu n'est pas rempli");
        $password = $this->request->input('password');
        if (!$password) return fail("Le mot de passe n'est pas rempli");

        $info = objectToArray(Shopping::getInstance()->where('card',$card)->where('password',$password)->first());

        if(empty($info)){
            return fail("identifiant ou mot de passe incorrect");
        }

        if($info['update'] > 1){
            return fail("La carte-cadeau a déjà été utilisée");
        }
        Db::beginTransaction();
        try{

            $user = objectToArray(Member::getInstance()->where('id',$uid)->first());

            Shopping::getInstance()->where('id',$info['id'])->update(['update'=>time(),'name'=>$user['username'],'account'=>$user['phone']]);

            $money = $user['money'] + $info['money'];
            Member::getInstance()->where('id',$user['id'])->update(['money'=>$money]);

            $data['uid'] = $uid;
            $data['card'] = 'Numéro de la carte-cadeau：'.$info['card'];
            $data['add_time'] = "Temps d'échange：".date('Y-m-d h:i:s',time());
            $data['money'] = $info['money'];
            $data['status'] = 1;
            Balance::getInstance()->insert($data);
            Db::commit();
        } catch(\Throwable $ex){
            Db::rollBack();
            return fail('La rédemption a échoué');
        }
        return fail('Rédemption réussie');

    }

    /**
     * User: liuan
     * Date: 2020/7/23 13:31
     * @PostApi(path="listCard", description="礼品卡规则")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":3,"name：名称":"礼品规则","title：标题":"1421","text：内容":"国喜爱奇特之物 美国人对礼品主要讲究实用性和奇特性。如果能送一些具有独特风格或...","time":null}})
     */
    public function listCard(){
        $data = objectToArray(About::getInstance()->where('id',3)->first());

        return success($data);

    }

    /**
     * @return array|int
     * User: liuan
     * Date: 2020/7/23 13:51
     * @PostApi(path="getResidue", description="账户余额")
     * @Query(key="uid|用户id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"money：余额":"80.00","user":{"id":1,"uid":1,"card：卡号":"礼品卡号：5934728106","add_time：时间":"兑换时间：2020-07-23 12:07:24","money：金额":"20.00","status：1余额添加2支付减少":1}}})
     */
    public function getResidue(){
        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");

        $data['money'] = Member::getInstance()->value('money');

        $data['user'] = Balance::getInstance()->where('uid',$uid)->get();

        return success($data);
    }

}
