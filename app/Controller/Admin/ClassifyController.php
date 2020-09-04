<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Broad;
use App\Model\Commodity;
use App\Model\Subclass;
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
 * @ApiController(tag="后台分类", description="大类添加/修改/列表/删除/小类添加/修改/列表/删除")
 */

class ClassifyController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/10 16:10
     * @PostApi(path="largeAdd", description="大类添加/修改")
     * @Query(key="id|有修改，无就添加", rule="")
     * @Query(key="name|名称", rule="required")
     * @Query(key="sort|排序", rule="required")
     * @Query(key="img|图片", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":1})
     */
    public function largeAdd(){
        $id = $this->request->input('id');

        $data['name'] = $this->request->input('name');
        if (!$data['name']) return fail("Le nom n'est pas renseigné");
        $data['sort'] = $this->request->input('sort');
        if (!$data['sort']) return fail("Le tri n'est pas rempli");
        $data['img'] = $this->request->input('img');
        if (!$data['img']) return fail("L'image n'est pas remplie");
        if(empty($id)){
            $data['add_time'] = time();
            $list = Broad::getInstance()->insert($data);
            if(!empty($list)){
                return success("Ajouté avec succès");
            }else{
                return fail('ajouter a échoué');
            }
        }else{
            $data['update'] = time();
            $list = Broad::getInstance()->where('id',$id)->update($data);
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
     * Date: 2020/7/10 16:12
     * @PostApi(path="largeList", description="大类列表")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":{"id":1,"name：名称":"电器","img：图片":"1.jpg","sort：排序":"231","add_time":1594368539,"update":null}})
     */
    public function largeList(){
        $data = Broad::getInstance()->orderBy('sort','desc')->get();
        return success($data);
    }
    /*
     * 大类删除
     */
    /**
     * @return array
     * User: liuan
     * Date: 2020/7/10 17:46
     * @PostApi(path="largeDelete", description="大类删除")
     * @Query(key="id|id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="删除成功", schema={"data":1})
     */
    public function largeDelete(){
        $id = $this->request->input('id');
        if (!$id) return fail('identifiant non rempli');

        $list = Subclass::getInstance()->where('big_id',$id)->get();
        if(count($list) > 0){
            return fail('Il existe des sous-catégories dans cette catégorie qui ne peuvent pas être supprimées');
        }
        $data = Broad::getInstance()->where('id',$id)->delete();
        if(!empty($data)){
            return success('supprimé avec succès');
        }else{
            return fail('échec de la suppression');
        }
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/10 17:44
     * @PostApi(path="subclassAdd", description="小类添加/修改")
     * @Query(key="id|有修改，无就添加", rule="")
     * @Query(key="big_id|大类id", rule="required")
     * @Query(key="name_little|名称", rule="required")
     * @Query(key="sort_little|排序", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":1})
     */
    public function subclassAdd(){
        $id = $this->request->input('id');

        $data['name_little'] = $this->request->input('name_little');
        if (!$data['name_little']) return fail("Le nom n'est pas renseigné");
        $data['big_id'] = $this->request->input('big_id');
        if (!$data['big_id']) return fail("L'identifiant de la catégorie principale n'est pas renseigné");
        $data['sort_little'] = $this->request->input('sort_little');
        if (!$data['sort_little']) return fail("Le tri n'est pas rempli");
        if(empty($id)){
            $data['add_time'] = time();
            $list = Subclass::getInstance()->insert($data);
            if(!empty($list)){
                return success("Ajouté avec succès");
            }else{
                return fail("ajouter a échoué");
            }
        }else{
            $data['update'] = time();
            $list = Subclass::getInstance()->where('id',$id)->update($data);
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
     * Date: 2020/7/10 17:57
     * @PostApi(path="subclassList", description="小类列表")
     * @Query(key="id|大类id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":{"id":1,"big_id：大类id":3,"name_little：名称":"裤子33","sort_little：排序":"33","add_time":1594369693,"update":1594369709}})
     */
    public function subclassList(){
        $id = $this->request->input('id');
        if (!$id) return fail("L'identifiant de la catégorie principale n'est pas renseigné");

        $data = Subclass::getInstance()->where('big_id',$id)->orderBy('sort_little','desc')->get();
        return success($data);
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/10 18:04
     * @PostApi(path="subclassDelete", description="小类删除")
     * @Query(key="id|id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="删除成功", schema={"data":1})
     */
    public function subclassDelete(){
        $id = $this->request->input('id');
        if (!$id) return fail('identifiant non rempli');

        $list = Commodity::getInstance()->where('little_id',$id)->get();
        if(count($list) > 0){
            return fail('Certains produits de cette catégorie ne peuvent pas être supprimés');
        }

        $data = Subclass::getInstance()->where('id',$id)->delete();
        if(!empty($data)){
            return success('supprimé avec succès');
        }else{
            return fail('échec de la suppression');
        }
    }


}
