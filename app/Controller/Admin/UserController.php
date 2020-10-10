<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Adminuser;
use App\Model\Authsss;
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
 * @ApiController(tag="后台用户", description="后台用户添加/修改/用户列表/删除/用户权限/权限列表")
 */
class UserController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/9 20:22
     * @PostApi(path="userAdd", description="用户添加/修改")
     * @Query(key="id|有修改，无就添加", rule="optional")
     * @Query(key="username|用户名", rule="required")
     * @Query(key="account|账号", rule="required")
     * @Query(key="password|密码", rule="required")
     * @Query(key="auth|权限", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":1})
     */
    public function userAdd(){
        $id = $this->request->input('id');

        $data['username'] = $this->request->input('username');
        if (!$data['username']) return fail("Le nom d'utilisateur est manquant");
        $data['account'] = $this->request->input('account');
        if (!$data['account']) return fail("Le compte n'est pas rempli");
        $data['password'] = $this->request->input('password');
        if (!$data['password']) return fail("Le mot de passe n'est pas rempli");
        $data['password'] = md5($data['password']);
        $data['auth'] = $this->request->input('auth');
        if (!$data['auth']) return fail("L'autorisation n'est pas remplie");

        if(empty($id)){
            $data['add_time'] = time();
            $list = Adminuser::getInstance()->insert($data);
            if(!empty($list)){
                return success('Ajouté avec succès');
            }else{
                return fail('ajouter a échoué');
            }
        }else{
            $data['update'] = time();
            $list = Adminuser::getInstance()->where('id',$id)->update($data);
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
     * Date: 2020/7/9 20:31
     * @PostApi(path="userList", description="用户列表")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":1,"username：名称":"admin","account：账号":"15007960733","password：密码":"e10adc3949ba59abbe56e057f20f883e","auth：权限":{"id":1,"name：名称":"首页","url：路由":"www.baidu.com"},"add_time":1594297658,"update":1594297722}})
     */
    public function userList(){
        $data = Adminuser::getInstance()->userList();
        return success($data);
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/9 20:37
     * @PostApi(path="userDelete", description="删除")
     * @Query(key="id|用户id", rule="required")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function userDelete(){
        $id = $this->request->input('id');
        if (!$id) return fail('identifiant non rempli');

        $data = Adminuser::getInstance()->where('id',$id)->delete();
        if(!empty($data)){
            return success('supprimé avec succès');
        }else{
            return fail('échec de la suppression');
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/18 12:10
     * @PostApi(path="Auth", description="权限列表")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":1,"name：名称":"首页","url：路由":"www.baidu.com","add_time：添加时间":1594949293}})
     */
    public function Auth(){
        $data = Authsss::getInstance()->orderBy('id','asc')->get();
        unset($data[0]);
        return success(array_values(objectToArray($data)));
    }

    /**
     * User: liuan
     * Date: 2020/7/18 12:15
     * @PostApi(path="userAuth", description="用户权限")
     * @Query(key="uid|用户id", rule="optional")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":{"id":1,"name：名称":"首页","url：路径":"www.baidu.com"}})
     */
    public function userAuth(){
        $uid = $this->request->input('uid');
        if (!$uid) return fail("L'ID utilisateur n'est pas renseigné");

        $user = objectToArray(Adminuser::getInstance()->where('id',$uid)->first());

        $arr = explode(',',$user['auth']);

        $data = Authsss::getInstance()->whereIn('id',$arr)->select('id','name','url')->get();
        return success($data);
    }
}
