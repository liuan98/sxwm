<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Model\Vehicle;
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
 * @ApiController(tag="前台购物车", description="添加购物车/购物车列表/购物车修改/购物车删除")
 */

class VehicleController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * User: liuan
     * Date: 2020/7/20 14:43
     * @PostApi(path="vehicleAdd", description="添加购物车")
     * @Query(key="goods_id|商品id", rule="required")
     * @Query(key="uid|用户id", rule="required")
     * @Query(key="num|数量", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function vehicleAdd(){
        $data['goods_id'] = $this->request->input('goods_id');
        if (!$data['goods_id']) return fail("L'identifiant du produit n'est pas renseigné");
        $data['uid'] = $this->request->input('uid');
        if (!$data['uid']) return fail("L'ID utilisateur n'est pas renseigné");
        $data['num'] = $this->request->input('num');
        if (!$data['num']) return fail("La quantité n'est pas remplie");

        $data['add_time'] = time();

        $list = Vehicle::getInstance()->insert($data);

        if(!empty($list)){
            return success('Ajouté avec succès');
        }else{
            return fail('ajouter a échoué');
        }

    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/20 14:53
     * @PostApi(path="vehicleList", description="购物车列表")
     * @Query(key="uid|用户id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"list":{"id":3,"goods_id":24,"name：名称":"幼儿|棉质裤子","title：标题":"","number：编号":"sxwm6074385912","money：展示价格":"10.00","price：市场价格":"0.00","standard：规格":"","attribute：0=>没有；1=>划算；2=>推荐":1,"label：标签":"","img：图片":"1.jpg","text：内容":"","status：1=>上架；2=>下架":"1","num：数量":2},"money：合计价格":20}})
     */
    public function vehicleList(){
        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");

        $data['list'] = Vehicle::getInstance()->vehicleList($uid);

        foreach($data['list'] as $item){
            $data['money'] += (int) $item['money'] * $item['num'];
        }

        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/7/20 15:11
     * @PostApi(path="vehicleUpdate", description="购物车修改")
     * @Query(key="id|id", rule="required")
     * @Query(key="num|数量", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function vehicleUpdate(){
        $id = $this->request->input('id');
        if (!$id) return fail('identifiant non rempli');

        $data['num'] = $this->request->input('num');
        if (!$data['num']) return fail("La quantité n'est pas remplie");

        $data['update'] = time();
        $list = Vehicle::getInstance()->where('id',$id)->update($data);
        if(!empty($list)){
            return success('Modifié avec succès');
        }else{
            return fail('échec de modifier');
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/20 15:26
     * @PostApi(path="vehicleDelete", description="购物车删除")
     * @Query(key="goods_id|1,2,3这样的", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function vehicleDelete(){
        $goods_id = $this->request->input('goods_id');
        if (!$goods_id) return fail('id未填写');

        $arr = explode(',',$goods_id);

        $list = Vehicle::getInstance()->whereIn('goods_id',$arr)->delete();
        if(!empty($list)){
            return success('删除成功');
        }else{
            return fail('删除失败');
        }

    }

}
