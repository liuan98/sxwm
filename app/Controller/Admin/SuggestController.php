<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Suggest;
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
 * @ApiController(tag="后台建议", description="建议列表/建议状态修改/建议状态删除")
 */

class SuggestController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/16 19:22
     * @PostApi(path="suggestList", description="建议列表")
     * @Query(key="id|分页", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function suggestList(){
        $id = max(intval($this->request->input('id')), 0);
        $id = $id && $id > 0 ? $id : 0;
        $where[] = $id > 0 ? ['id', '<', $id] : ['id', '>', 0];

        $data = Suggest::getInstance()->suggestList($where);

        return success($data);
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/16 19:36
     * @PostApi(path="suggestStatus", description="建议状态修改")
     * @Query(key="id|id", rule="required")
     * @Query(key="status|1开启2禁用", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function suggestStatus(){
        $data = $this->request->all();
        if (!$data['id']) return fail('identifiant non rempli');
        if (!$data['status']) return fail("Le statut n'est pas rempli");

        $data['update'] = time();
        $list = Suggest::getInstance()->where('id',$data['id'])->update($data);
        if(!empty($list)){
            return success('Modifié avec succès');
        }else{
            return fail('échec de modifier');
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/16 19:38
     * @PostApi(path="suggestDelete", description="建议状态删除")
     * @Query(key="id|id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function suggestDelete(){
        $id = $this->request->input("id");
        if (!$id) return fail('identifiant non rempli');
        $list = Suggest::getInstance()->where('id',$id)->delete();
        if(!empty($list)){
            return success('supprimé avec succès');
        }else{
            return fail('échec de la suppression');
        }
    }

}
