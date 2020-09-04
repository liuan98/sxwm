<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Model\Search;
use App\Model\Site;
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
 * @ApiController(tag="前台收货地址", description="添加修改收货地址/收货地址列表/修改默认收货地址/删除收货地址/默认收货地址")
 */

class SiteController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * User: liuan
     * Date: 2020/7/20 16:41
     * @PostApi(path="siteAdd", description="添加修改收货地址")
     * @Query(key="id|有就修改", rule="")
     * @Query(key="uid|用户id", rule="required")
     * @Query(key="name|名称", rule="required")
     * @Query(key="phone|电话", rule="required")
     * @Query(key="site|地址", rule="required")
     * @Query(key="detail|详细地址", rule="required")
     * @Query(key="label|标签", rule="required")
     * @Query(key="status|1默认2非默认", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function siteAdd(){
        $data = $this->request->all();
        if (!$data['uid']) return fail("L'ID utilisateur n'est pas renseigné");
        if (!$data['name']) return fail("Le nom n'est pas renseigné");
        if (!$data['phone']) return fail("Le téléphone n'est pas rempli");
        if (!$data['site']) return fail("L'adresse n'est pas renseignée");
        if (!$data['detail']) return fail("L'adresse détaillée n'est pas renseignée");
        if (!$data['label']) return fail("L'étiquette n'est pas remplie");
        if (!$data['status']) return fail("Non renseigné par défaut");
        $list['add_time'] = time();

        if(empty($data['id'])){
            $user = objectToArray(Site::getInstance()->where('uid',$data['uid'])->where('status',1)->first());

            $new['status'] = 2;
            Site::getInstance()->where('id',$user['id'])->update($new);

            $data['add_time'] = time();
            $list = Site::getInstance()->insert($data);
            if(!empty($list)){
                return success('Ajouté avec succès');
            }else{
                return fail('ajouter a échoué');
            }
        }else{

            $user = objectToArray(Site::getInstance()->where('uid',$data['uid'])->where('status',1)->first());

            $new['status'] = 2;
            Site::getInstance()->where('id',$user['id'])->update($new);

            $data['update'] = time();
            $list = Site::getInstance()->where('id',$data['id'])->update($data);
            if(!empty($list)){
                return success('Modifié avec succès');
            }else{
                return fail('échec de modifier');
            }
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/20 17:00
     * @PostApi(path="siteList", description="收货地址列表")
     * * @Query(key="uid|用户id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":3,"uid：用户id":1,"name：名称":"老王233","phone：电话":"110","site：地址":"江西南昌","detail：详细地址":"红谷滩万达b3 1313","label：标签":"其他124","status：1默认2非默认":2,"add_time":1595235512,"update":1595235601}})
     */
    public function siteList(){
        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");

        $data = Site::getInstance()->where('uid',$uid)->orderBy('id','desc')->get();

        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/7/20 17:31
     * @PostApi(path="siteUpdate", description="修改默认收货地址")
     * @Query(key="id|id", rule="required")
     * @Query(key="uid|用户id", rule="required")
     * @Query(key="status|1默认2非默认", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function siteUpdate(){
        $id = $this->request->input('id');
        if (!$id) return fail('identifiant non rempli');
        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");
        $status = $this->request->input('status');
        if (!$status) return fail("Le statut n'est pas rempli");

        $user = objectToArray(Site::getInstance()->where('uid',$uid)->where('status',1)->first());

        $new['status'] = 2;
        Site::getInstance()->where('id',$user['id'])->update($new);

        $data['status'] = $status;
        $data['update'] = time();
        $list = Site::getInstance()->where('id',$id)->update($data);
        if(!empty($list)){
            return success('Modifié avec succès');
        }else{
            return fail('échec de modifier');
        }

    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/20 17:53
     * @PostApi(path="siteDelete", description="删除收货地址")
     * @Query(key="id|id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function siteDelete(){
        $id = $this->request->input('id');
        if (!$id) return fail('identifiant non rempli');

        $list = Site::getInstance()->where('id',$id)->delete();
        if(!empty($list)){
            return success('supprimé avec succès');
        }else{
            return fail('échec de la suppression');
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/20 18:01
     * @PostApi(path="default", description="默认收货地址")
     * @Query(key="uid|用户id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":3,"uid：用户id":1,"name：名称":"老王233","phone：电话":"110","site：地址":"江西南昌","detail：详细地址":"红谷滩万达b3 1313","label：标签":"其他124","status：1默认2非默认":2,"add_time":1595235512,"update":1595235601}})
     */
    public function default(){
        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");

        $data = objectToArray(Site::getInstance()->where('status',1)->where('uid',$uid)->first());

        return success($data);
    }

}
