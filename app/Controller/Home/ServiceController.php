<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Model\After;
use App\Model\Complain;
use App\Model\Member;
use App\Model\System;
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
 * @ApiController(tag="前台售后服务", description="投诉建议/拍照索赔")
 */
class ServiceController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * User: liuan
     * Date: 2020/7/23 9:51
     * @PostApi(path="getComplain", description="投诉建议")
     * @Query(key="uid|用户id", rule="required")
     * @Query(key="text|内容", rule="required")
     * @Query(key="phone|电话", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function getComplain(){
        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");

        $data['text'] = $this->request->input('text');
        if (!$data['text']) return fail("Le contenu n'est pas rempli");
        $data['phone'] = $this->request->input('phone');
        if (!$data['phone']) return fail("Le téléphone n'est pas rempli");

        $data['add_time'] = time();

        $data['name'] = Member::getInstance()->where('id',$uid)->value('username');

        $list = Complain::getInstance()->insert($data);
        if(!empty($list)){
            return success('Ajouté avec succès');
        }else{
            return fail('ajouter a échoué');
        }

    }

    /**
     * User: liuan
     * Date: 2020/7/23 9:47
     * @PostApi(path="getAfter", description="拍照索赔")
     * @Query(key="uid|用户id", rule="required")
     * @Query(key="mark|订单号", rule="required")
     * @Query(key="phone|电话", rule="required")
     * @Query(key="img|图片", rule="required")
     * @Query(key="text|内容", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function getAfter(){
        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");

        $data['mark'] = $this->request->input('mark');
        if (!$data['mark']) return fail("Le numéro de commande n'est pas renseigné");
        $data['phone'] = $this->request->input('phone');
        if (!$data['phone']) return fail("Le téléphone n'est pas rempli");
        $data['img'] = $this->request->input('img');
        if (!$data['img']) return fail("L'image n'est pas remplie");
        $data['text'] = $this->request->input('phone');
        if (!$data['text']) return fail("Le contenu n'est pas rempli");

        $data['add_time'] = time();

        $data['name'] = Member::getInstance()->where('id',$uid)->value('username');

        $list = After::getInstance()->insert($data);
        if(!empty($list)){
            return success('Ajouté avec succès');
        }else{
            return fail('ajouter a échoué');
        }
    }

}
