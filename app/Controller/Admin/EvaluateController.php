<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\App;
use App\Model\Evaluate;
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
 * @ApiController(tag="后台评价管理", description="评价列表/评价状态修改/评价状态删除/app评价列表/app评价删除")
 */
class EvaluateController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * User: liuan
     * Date: 2020/7/16 17:48
     * @PostApi(path="evaluateList", description="评价列表")
     * @Query(key="id|分页", rule="")
     * @Query(key="name|搜索", rule="")
     * @Query(key="time|时间", rule="")
     * @Query(key="status|1未审核2成功3失败", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":5,"name：名称":"评价5","phone：电话":"150","serial：编号":"23523566","text：内容":"哈哈哈哈","level：星级":"1","status：1未审核2成功3失败":1,"add_time":1594881384}})
     */
    public function evaluateList(){
        $id = max(intval($this->request->input('id')), 0);
        $id = $id && $id > 0 ? $id : 0;
        $where[] = $id > 0 ? ['id', '<', $id] : ['id', '>', 0];

        $name = $this->request->input("name");

        $time = intval($this->request->input("time"));

        $status = $this->request->input("status");

        $data = Evaluate::getInstance()->evaluateList($where,$name,$time,$status);

        return success($data);
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/16 19:00
     * @PostApi(path="evaluateUpdate", description="评价状态修改")
     * @Query(key="id|id", rule="required")
     * @Query(key="status|1未审核2成功3失败", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function evaluateUpdate(){
        $data = $this->request->all();
        if (!$data['id']) return fail('identifiant non rempli');
        if (!$data['status']) return fail("Le statut n'est pas rempli");

        $list = Evaluate::getInstance()->where('id',$data['id'])->update($data);
        if(!empty($list)){
            return success('Modifié avec succès');
        }else{
            return fail('échec de modifier');
        }
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/16 19:03
     * @PostApi(path="evaluateDelete", description="评价状态删除")
     * @Query(key="id|id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function evaluateDelete(){
        $id = $this->request->input("id");
        if (!$id) return fail('identifiant non rempli');
        $list = Evaluate::getInstance()->where('id',$id)->delete();
        if(!empty($list)){
            return success('supprimé avec succès');
        }else{
            return fail('échec de la suppression');
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/17 11:52
     * @PostApi(path="appList", description="app评价列表")
     * @Query(key="id|id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function appList(){
        $id = max(intval($this->request->input('id')), 0);
        $id = $id && $id > 0 ? $id : 0;
        $where[] = $id > 0 ? ['id', '<', $id] : ['id', '>', 0];

        $time = intval($this->request->input("time"));

        $data = App::getInstance()->addList($where,$time);

        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/7/17 12:03
     * @PostApi(path="appDelete", description="app评价删除")
     * @Query(key="id|id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function appDelete(){
        $id = $this->request->input("id");
        if (!$id) return fail('identifiant non rempli');
        $list = App::getInstance()->where('id',$id)->delete();
        if(!empty($list)){
            return success('supprimé avec succès');
        }else{
            return fail('échec de la suppression');
        }
    }
}
