<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Count;
use App\Model\Invite;
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
use Hyperf\DbConnection\Db;

/**
 * @ApiController(tag="后台邀请有礼", description="邀请规则列表/邀请规则修改/邀请记录/邀请记录删除")
 */
class InviteController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * User: liuan
     * Date: 2020/7/17 15:11
     * @PostApi(path="inviteList", description="邀请有礼列表")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function inviteList(){
        $data = objectToArray(Invite::getInstance()->where('id',1)->first());

        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/7/17 15:15
     * @PostApi(path="inviteUpdate", description="邀请规则修改")
     * @Query(key="id|id", rule="required")
     * @Query(key="text|内容", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function inviteUpdate(){
        $id = $this->request->input("id");
        if (!$id) return fail('identifiant non rempli');
        $data['text'] = $this->request->input("text");
        if (!$data['text']) return fail("Le contenu n'est pas rempli");

        $data['update'] = time();

        $list = Invite::getInstance()->where('id',$id)->update($data);

        if(!empty($list)){
            return success('Modifié avec succès');
        }else{
            return fail('échec de modifier');
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/17 15:24
     * @PostApi(path="InvitedRecord", description="邀请记录")
     * @Query(key="number|分页", rule="")
     * @Query(key="name|搜索", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"uid：用户id":3,"name：名称":"ggg","phone：电话":"130","num：邀请人数":1}})
     */
    public function InvitedRecord(){
        $number = max(intval($this->request->input('number')), 0);
        if($number > 0){
            $num = '<';
        }else{
            $num = '>';
        }
        $name = $this->request->input("name");
        $where[] = ['u.username', 'like', '%'.$name.'%'];

        $data = Count::getInstance()->InvitedRecord($where,$number,$num);

        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/7/21 9:37
     * @PostApi(path="invitedDelete", description="邀请记录删除")
     * @Query(key="uid|用户id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function invitedDelete(){
        $uid = $this->request->input('uid');
        if (!$uid) return fail('non rempli');

        $list = Count::getInstance()->where('uid',$uid)->delete();
        if(!empty($list)){
            return success('supprimé avec succès');
        }else{
            return fail('échec de la suppression');
        }
    }

}
