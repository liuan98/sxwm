<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Commodity;
use App\Model\Confirm;
use App\Model\Member;
use App\Model\People;
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
 * @ApiController(tag="后台首页", description="新人引导展示/修改/系统设置展示/修改/首页")
 */
class AdminController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/10 11:22
     * @PostApi(path="people", description="新人引导展示/修改")
     * @Query(key="id|", rule="optional")
     * @Query(key="text|标题", rule="optional")
     * @Query(key="img|图片", rule="optional")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":{"id":1,"text：标题":"123235423","img：图片":"1.jpg","add_time":1594192435,"update":1594352806}})
     */
    public function people(){
        $id = $this->request->input('id');

        if(empty($id)){
            $data = objectToArray(People::getInstance()->where('id',1)->first());
            return success($data);
        }else{
            $data['text'] = $this->request->input('text');
            if (!$data['text']) return fail('Le titre est manquant');
            $data['img'] = $this->request->input('img');
            if (!$data['img']) return fail("L'image n'est pas remplie");

            $data['update'] = time();
            $list = People::getInstance()->where('id',$id)->update($data);
            if(!empty($list)){
                return success('Modifié avec succès');
            }else{
                return fail('échec de modifier');
            }
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/10 11:48
     * @PostApi(path="System", description="系统设置展示/修改")
     * @Query(key="id|", rule="optional")
     * @Query(key="begin|上班时间", rule="optional")
     * @Query(key="finish|下班时间", rule="optional")
     * @Query(key="phone|电话", rule="optional")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":{"id":1,"begin：上班时间":"8:00","finish：下班时间":"18:00","phone：电话":"15007960733","add_time":1594192435,"update":null}})
     */
    public function System(){
        $id = $this->request->input('id');

        if(empty($id)){
            $data = objectToArray(System::getInstance()->where('id',1)->first());
            return success($data);
        }else{
            $data['begin'] = $this->request->input('begin');
            if (!$data['begin']) return fail('Les heures de travail ne sont pas renseignées');
            $data['finish'] = $this->request->input('finish');
            if (!$data['finish']) return fail('Ne pas remplir les heures creuses');
            $data['phone'] = $this->request->input('phone');
            if (!$data['phone']) return fail("Le téléphone n'est pas rempli");

            $data['update'] = time();
            $list = System::getInstance()->where('id',$id)->update($data);
            if(!empty($list)){
                return success('Modifié avec succès');
            }else{
                return fail('échec de modifier');
            }
        }

    }

    /**
     * User: liuan
     * Date: 2020/7/23 15:44
     * @PostApi(path="getPage", description="首页")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":{"user：商城用户":7,"today：今日订单":1,"goods：商品统计":{"num：所有商品":2,"putaway：上架":2,"sold：下架":0},"order：":{"send：待发货":1,"unpaid：待结算":0,"make：已成交":0,"defeated：交易失败":0}}})
     */
    public function getPage(){
        $data['user'] = Member::getInstance()->count();//商城用户

        $data['today'] = Confirm::getInstance()->today();//今日订单

        $data['goods'] = Commodity::getInstance()->goodsPage();//商品统计

        $data['order'] = Confirm::getInstance()->orderPage();//今日订单统计
        return success($data);
    }

}
