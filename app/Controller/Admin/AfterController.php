<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\After;
use App\Model\Complain;
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
 * @ApiController(tag="后台售后服务管理", description="售后服务列表/售后服务状态修改/售后服务删除/投诉列表/投诉状态修改/投诉删除")
 */

class AfterController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * User: liuan
     * Date: 2020/7/17 10:07
     * @PostApi(path="afterList", description="售后服务列表")
     * @Query(key="id|分页", rule="")
     * @Query(key="status|1待处理2已处理", rule="required")
     * @Query(key="name|搜索", rule="")
     * @Query(key="time|时间", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":4,"name：名称":"索赔4","phone：电话":"140","mark：订单号":"235235","text：内容":"ffsafas","img：图片":"1.jpg","status：1待处理2已处理":1,"add_time：添加时间":1594951339,"update":null}})
     */
    public function afterList(){
        $id = max(intval($this->request->input('id')), 0);
        $id = $id && $id > 0 ? $id : 0;
        $where[] = $id > 0 ? ['id', '<', $id] : ['id', '>', 0];

        $name = $this->request->input("name");
        $where[] = ['name', 'like', '%'.$name.'%'];

        $time = intval($this->request->input("time"));

        $status = $this->request->input("status");

        $data = After::getInstance()->afterList($where,$time,$status);

        return success($data);
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/17 10:38
     * @PostApi(path="afterStatus", description="售后服务状态修改")
     * @Query(key="id|id", rule="required")
     * @Query(key="status|1待处理2已处理", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function afterStatus(){
        $data = $this->request->all();
        if (!$data['id']) return fail('identifiant non rempli');
        if (!$data['status']) return fail("Le statut n'est pas rempli");

        $data['update'] = time();
        $list = After::getInstance()->where('id',$data['id'])->update($data);
        if(!empty($list)){
            return success('Modifié avec succès');
        }else{
            return fail('échec de modifier');
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/17 10:39
     * @PostApi(path="afterDelete", description="售后服务删除")
     * @Query(key="id|id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function afterDelete(){
        $id = $this->request->input("id");
        if (!$id) return fail('identifiant non rempli');

        $list = After::getInstance()->where('id',$id)->delete();
        if(!empty($list)){
            return success('supprimé avec succès');
        }else{
            return fail('échec de la suppression');
        }
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/17 10:55
     * @PostApi(path="complainList", description="投诉列表")
     * @Query(key="id|分页", rule="")
     * @Query(key="status|1待处理2已处理", rule="required")
     * @Query(key="name|搜索", rule="")
     * @Query(key="time|时间", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function complainList(){
        $id = max(intval($this->request->input('id')), 0);
        $id = $id && $id > 0 ? $id : 0;
        $where[] = $id > 0 ? ['id', '<', $id] : ['id', '>', 0];

        $name = $this->request->input("name");
        $where[] = ['name', 'like', '%'.$name.'%'];

        $time = intval($this->request->input("time"));

        $status = $this->request->input("status");

        $data = Complain::getInstance()->complainList($where,$time,$status);

        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/7/17 11:03
     * @PostApi(path="complainStatus", description="投诉状态修改")
     * @Query(key="id|id", rule="required")
     * @Query(key="status|1待处理2已处理", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function complainStatus(){
        $data = $this->request->all();
        if (!$data['id']) return fail('id未填写');
        if (!$data['status']) return fail('状态未填写');

        $data['update'] = time();
        $list = Complain::getInstance()->where('id',$data['id'])->update($data);
        if(!empty($list)){
            return success('identifiant non rempli');
        }else{
            return fail('échec de modifier');
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/17 11:05
     * @PostApi(path="complainDelete", description="投诉删除")
     * @Query(key="id|id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function complainDelete(){
        $id = $this->request->input("id");
        if (!$id) return fail('identifiant non rempli');

        $list = Complain::getInstance()->where('id',$id)->delete();
        if(!empty($list)){
            return success('supprimé avec succès');
        }else{
            return fail('échec de la suppression');
        }
    }

}
