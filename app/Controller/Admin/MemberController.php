<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Member;
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
 * @ApiController(tag="后台会员管理", description="会员列表/会员修改/会员删除/会员状态修改")
 */
class MemberController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/16 16:25
     * @PostApi(path="memberList", description="会员列表")
     * @Query(key="id|分页", rule="")
     * @Query(key="name|搜索", rule="")
     * @Query(key="time|时间", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function memberList(){
        $id = max(intval($this->request->input('id')), 0);
        $id = $id && $id > 0 ? $id : 0;
        $where[] = $id > 0 ? ['id', '<', $id] : ['id', '>', 0];

        $name = $this->request->input("name");

        $time = intval($this->request->input("time"));

        $data = Member::getInstance()->memberList($where,$name,$time);

        return success($data);
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/16 16:35
     * @PostApi(path="memberUpdate", description="会员修改")
     * @Query(key="id|id", rule="required")
     * @Query(key="username|名称:3选一修改", rule="")
     * @Query(key="phone|电话:3选一修改", rule="")
     * @Query(key="money|余额:3选一修改", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function memberUpdate(){
        $data = $this->request->all();

        if (!$data['id']) return fail('identifiant non rempli');

        $data['update'] = time();
        $list = Member::getInstance()->where('id',$data['id'])->update($data);
        if(!empty($list)){
            return success('Modifié avec succès');
        }else{
            return fail('échec de modifier');
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/16 17:15
     * @PostApi(path="memberDelete", description="会员删除")
     * @Query(key="id|1,2,3这样的", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function memberDelete(){
        $id = $this->request->input("id");
        if (!$id) return fail('identifiant non rempli');
        $list = explode(',',$id);
        $list = Member::getInstance()->whereIn('id',$list)->delete();
        if(!empty($list)){
            return success('supprimé avec succès');
        }else{
            return fail('échec de la suppression');
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/16 17:18
     * @PostApi(path="memberStatus", description="会员状态修改")
     * @Query(key="id|id", rule="required")
     * @Query(key="status|1开启2禁用", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function memberStatus(){
        $data = $this->request->all();
        if (!$data['id']) return fail('identifiant non rempli');
        if (!$data['status']) return fail("Le statut n'est pas rempli");

        $data['update'] = time();
        $list = Member::getInstance()->where('id',$data['id'])->update($data);
        if(!empty($list)){
            return success('Modifié avec succès');
        }else{
            return fail('échec de modifier');
        }
    }
}
