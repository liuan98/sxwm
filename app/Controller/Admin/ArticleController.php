<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\About;
use App\Model\Contact;
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
 * @ApiController(tag="后台文章列表", description="关于我们展示/修改/联系我们添加/修改/列表/删除")
 */

class ArticleController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/10 19:05
     * @PostApi(path="aboutList", description="关于我们展示/修改")
     * @Query(key="id|id", rule="")
     * @Query(key="name|名称", rule="required")
     * @Query(key="title|标题", rule="required")
     * @Query(key="text|内容", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":1})
     */
    public function aboutList(){
        $id = $this->request->input('id');

        if(empty($id)){
            $list = About::getInstance()->get();
            return success($list);
        }else{
            $data['name'] = $this->request->input('name');
            if (!$data['name']) return fail("Le nom n'est pas renseigné");
            $data['title'] = $this->request->input('title');
            if (!$data['title']) return fail("Le titre est manquant");
            $data['text'] = $this->request->input('text');
            if (!$data['text']) return fail("Le contenu n'est pas rempli");

            $data['time'] = time();
            $list = About::getInstance()->where('id',$id)->update($data);
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
     * Date: 2020/7/10 19:27
     * @PostApi(path="contactAdd", description="联系我们添加/修改")
     * @Query(key="id|id有就修改", rule="")
     * @Query(key="name|名称", rule="required")
     * @Query(key="title|标题", rule="required")
     * @Query(key="text|内容", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":1})
     */
    public function contactAdd(){
        $id = $this->request->input('id');

        $data['name'] = $this->request->input('name');
        if (!$data['name']) return fail("Le nom n'est pas renseigné");
        $data['title'] = $this->request->input('title');
        if (!$data['title']) return fail('Le titre est manquant');
        $data['text'] = $this->request->input('text');
        if (!$data['text']) return fail("Le contenu n'est pas rempli");
        if(empty($id)){
            $data['time'] = time();
            $list = Contact::getInstance()->insert($data);
            if(!empty($list)){
                return success('Ajouté avec succès');
            }else{
                return fail('ajouter a échoué');
            }
        }else{
            $list = Contact::getInstance()->where('id',$id)->update($data);
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
     * Date: 2020/7/10 19:29
     * @PostApi(path="contactList", description="列表")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":1,"name：名称":"la","title：标题":"baiti","text：内容":"12542363467","time":1594380347}})
     */
    public function contactList(){
        $data = Contact::getInstance()->get();
        return success($data);
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/10 19:34
     * @PostApi(path="contactDelete", description="删除")
     * @Query(key="id|id有就修改", rule="")
     * @Query(key="name|名称", rule="required")
     * @Query(key="title|标题", rule="required")
     * @Query(key="text|内容", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":1})
     */
    public function contactDelete(){
        $id = $this->request->input('id');
        if (!$id) return fail('identifiant non rempli');

        $data = Contact::getInstance()->where('id',$id)->delete();
        if(!empty($data)){
            return success('supprimé avec succès');
        }else{
            return fail('échec de la suppression');
        }
    }

}
