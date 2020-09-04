<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Warehouse;
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
 * @ApiController(tag="后台仓库管理", description="仓库添加/修改/列表/删除")
 */

class WarehouseController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/10 14:08
     * @PostApi(path="warehouseAdd", description="仓库添加/修改")
     * @Query(key="id|有修改，无就添加", rule="")
     * @Query(key="name|仓库名称", rule="required")
     * @Query(key="area_name|区域", rule="required")
     * @Query(key="area|经纬度", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":1})
     */
    public function warehouseAdd(){
        $id = $this->request->input('id');

        $data['name'] = $this->request->input('name');
        if (!$data['name']) return fail("Le nom de l'entrepôt est manquant");
        $data['area_name'] = $this->request->input('area_name');
        if (!$data['area_name']) return fail('Champ non rempli');
        $data['area'] = $this->request->input('area');
//        if (!$data['area']) return fail('经纬度未填写');

        if(empty($id)){
            $data['add_time'] = time();
            $list = Warehouse::getInstance()->insert($data);
            if(!empty($list)){
                return success('Ajouté avec succès');
            }else{
                return fail('ajouter a échoué');
            }
        }else{
            $data['update'] = time();
            $list = Warehouse::getInstance()->where('id',$id)->update($data);
            if(!empty($list)){
                return success('Modifié avec succès');
            }else{
                return fail('échec de modifier');
            }
        }
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/10 14:35
     * @PostApi(path="warehouseList", description="仓库列表")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":1,"name：仓库名":"仓库1","area_name：区域名":"江西南昌红谷滩万达广场","area：经纬度":"12898120.55,3315255.29,14.68z","add_time":1594362002,"update":1594362096}})
     */
    public function warehouseList(){
        $data = Warehouse::getInstance()->get();
        return success($data);
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/10 14:41
     * @PostApi(path="warehouseDelete", description="删除")
     * @Query(key="id|id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function warehouseDelete(){
        $id = $this->request->input('id');
        if (!$id) return fail('identifiant non rempli');

        $data = Warehouse::getInstance()->where('id',$id)->delete();

        if(!empty($data)){
            return success('supprimé avec succès');
        }else{
            return fail('échec de la suppression');
        }
    }


}
