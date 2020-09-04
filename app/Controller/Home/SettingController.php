<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Model\About;
use App\Model\App;
use App\Model\Member;
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
 * @ApiController(tag="前台设置", description="服务与隐私/评价app")
 */

class SettingController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * User: liuan
     * Date: 2020/7/22 9:31
     * @PostApi(path="getProtocol", description="服务与隐私")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"privacy：隐私":{"id":1,"name：名称":"隐私协议","title：标题":"baiti","text：文本":"12542363467","time":1594379107},"serve：服务":{"id":2,"name：名称":"服务协议","title：标题":"2","text：文本":"2","time":2}}})
     */
    public function getProtocol(){
        $data['privacy'] = objectToArray(About::getInstance()->where('id',1)->first());

        $data['serve'] = objectToArray(About::getInstance()->where('id',2)->first());

        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/7/22 9:41
     * @PostApi(path="getAppraise", description="评价app")
     * @Query(key="uid|用户id", rule="required")
     * @Query(key="text|内容", rule="required")
     * @Query(key="star|星级", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function getAppraise(){
        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");

        $list = objectToArray(Member::getInstance()->where('id',$uid)->first());

        $data['name'] = $list['username'];
        $data['phone'] = $list['phone'];
        $data['add_time'] = time();

        $last['first'] = strtotime('-7 days');
        $last['end'] = time();

        $new = App::getInstance()->time($list,$last);
        if(count($new) > 0){
            return fail('Revu sous 7 jours');
        }
        $data['text'] = $this->request->input('text');
        if (!$data['text']) return fail("Le contenu n'est pas rempli");
        $data['star'] = $this->request->input('star');
        if (!$data['star']) return fail("Le nombre d'étoiles n'est pas rempli");


        $list = App::getInstance()->insert($data);
        if(!empty($list)){
            return success("Ajouté avec succès");
        }else{
            return fail('ajouter a échoué');
        }
    }


}
