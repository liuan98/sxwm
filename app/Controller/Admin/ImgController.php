<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Button;
use App\Model\Img;
use App\Model\Spread;
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
 * @ApiController(tag="后台图片管理", description="图片添加/修改/图片列表/图片删除/图片状态修改/功能按钮添加修改/功能按钮列表/功能按钮删除/功能按钮状态修改/开屏页修改/开屏页")
 */

class ImgController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/16 11:27
     * @PostApi(path="imgAdd", description="图片添加/修改")
     * @Query(key="id|有就修改", rule="")
     * @Query(key="sort|排序", rule="required")
     * @Query(key="name|名称", rule="required")
     * @Query(key="img|图片", rule="required")
     * @Query(key="url|链接", rule="required")
     * @Query(key="status|状态=1启用2禁用", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":1})
     */
    public function imgAdd(){
        $data = $this->request->all();
        if (!$data['sort']) return fail("Le tri n'est pas rempli");
        if (!is_numeric($data['sort'])) return fail("Le tri ne peut remplir que des nombres");
        if (!$data['name']) return fail("Le nom n'est pas renseigné");
        if (!$data['img']) return fail("L'image n'est pas remplie");
        if (!$data['url']) return fail("Le lien n'est pas rempli");
//        if (!$data['url_name']) return fail('链接名称未填写');
        if (!$data['status']) return fail("Le statut n'est pas rempli");

        if(empty($data['id'])){
            $data['add_time'] = time();

            $list = Img::getInstance()->insert($data);
            if(!empty($list)){
                return success('Ajouté avec succès');
            }else{
                return fail('ajouter a échoué');
            }
        }else{
            $data['update'] = time();
            $list = Img::getInstance()->where('id',$data['id'])->update($data);
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
     * Date: 2020/7/16 11:46
     * @PostApi(path="imgList", description="图片列表")
     * @Query(key="sort|分页", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":7,"sort：排序":"2789544","name：名称":"图片22","img：图片":"2.jpg","url_name：链接":"京东","url：链接":"www.jd.com","status：1启用2禁用":1,"add_time":1594871128,"update":null}})
     */
    public function imgList(){
        $sort = max(intval($this->request->input('sort')), 0);
        $sort = $sort && $sort > 0 ? $sort : 0;
        $where[] = $sort > 0 ? ['sort', '<', $sort] : ['sort', '>', 0];

        $data = Img::getInstance()->imgList($where);

        return success($data);
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/16 14:13
     * @PostApi(path="imgDelete", description="图片删除")
     * @Query(key="id|多条1,2,3这样传", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="删除成功", schema={"data":1})
     */
    public function imgDelete(){
        $id = $this->request->input("id");
        if (!$id) return fail('identifiant non rempli');
        $list = explode(',',$id);
        $list = Img::getInstance()->whereIn('id',$list)->delete();
        if(!empty($list)){
            return success('supprimé avec succès');
        }else{
            return fail('échec de la suppression');
        }

    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/16 17:04
     * @PostApi(path="imgStatus", description="图片状态修改")
     * @Query(key="id|id", rule="required")
     * * @Query(key="status|1开启2禁用", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function imgStatus(){
        $data = $this->request->all();
        if (!$data['id']) return fail('identifiant non rempli');
        if (!$data['status']) return fail("Le statut n'est pas rempli");

        $data['update'] = time();
        $list = Img::getInstance()->where('id',$data['id'])->update($data);
        if(!empty($list)){
            return success('Modifié avec succès');
        }else{
            return fail('échec de modifier');
        }
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/16 15:22
     * @PostApi(path="functionAdd", description="功能按钮添加/修改")
     * @Query(key="id|有就修改", rule="")
     * @Query(key="name|描述", rule="required")
     * @Query(key="img|图片", rule="required")
     * @Query(key="url|链接", rule="required")
     * @Query(key="status|状态=1启用2禁用", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":1})
     */
    public function functionAdd(){
        $data = $this->request->all();

        if (!$data['name']) return fail("Le nom n'est pas renseigné");
        if (!$data['img']) return fail("L'image n'est pas remplie");
        if (!$data['url']) return fail("Le lien n'est pas rempli");
        if (!$data['status']) return fail("Le statut n'est pas rempli");

        $info = Button::getInstance()->get();
        $new = count($info);
        if($new >= 9){
            return fail("9 éléments ont été ajoutés");
        }

        if(empty($data['id'])){
            unset($data['id']);
            $data['add_time'] = time();

            $list = Button::getInstance()->insert($data);
            if(!empty($list)){
                return success('Ajouté avec succès');
            }else{
                return fail('ajouter a échoué');
            }
        }else{
            $data['update'] = time();
            $list = Button::getInstance()->where('id',$data['id'])->update($data);
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
     * Date: 2020/7/16 15:33
     * @PostApi(path="functionList", description="功能按钮列表")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":3,"name：描述":"描述124215","img：图片":"1.jpg","url：链接":"www.baidu.com","status：1开启2禁用":1,"add_time：添加时间":1594884360,"update":1594884545}})
     */
    public function functionList(){
        $data = Button::getInstance()->orderBy('id','desc')->get();

        return success($data);
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/16 15:37
     * @PostApi(path="functionDelete", description="功能按钮删除")
     * @Query(key="id|id", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function functionDelete(){
        $id = $this->request->input("id");
        if (!$id) return fail('identifiant non rempli');

        $list = Button::getInstance()->where('id',$id)->delete();
        if(!empty($list)){
            return success('supprimé avec succès');
        }else{
            return fail('échec de la suppression');
        }
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/16 17:04
     * @PostApi(path="functionStatus", description="功能按钮状态修改")
     * @Query(key="id|id", rule="required")
     * @Query(key="status|1开启2禁用", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function functionStatus(){
        $data = $this->request->all();
        if (!$data['id']) return fail('identifiant non rempli');
        if (!$data['status']) return fail("Le statut n'est pas rempli");

        $data['update'] = time();
        $list = Button::getInstance()->where('id',$data['id'])->update($data);
        if(!empty($list)){
            return success('Modifié avec succès');
        }else{
            return fail('échec de modifier');
        }
    }

    /**
     * User: liuan
     * Date: 2020/8/10 14:46
     * @PostApi(path="alterSpread", description="开屏页修改")
     * @Query(key="img|图片", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function alterSpread(){
        $data['img'] = $this->request->input("img");
        if (!$data['img']) return fail("L'image n'est pas remplie");
        $data['update'] = time();
        $list = Spread::getInstance()->where('id',1)->update($data);
        if(!empty($list)){
            return success('Modifié avec succès');
        }else{
            return fail('échec de modifier');
        }
    }

    /**
     * User: liuan
     * Date: 2020/8/10 13:51
     * @PostApi(path="spread", description="开屏页")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":1,"img：图片":"1.jpg","update":12421}})
     */
    public function spread(){
        $data = objectToArray(Spread::getInstance()->where('id',1)->first());
        return success($data);
    }

}
