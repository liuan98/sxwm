<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Adminuser;
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
use Phper666\JwtAuth\Jwt;

/**
 * @ApiController(tag="后台登录", description="后台登录")
 */

class LoginController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * @param Jwt $jwt
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * User: liuan
     * Date: 2020/7/9 21:04
     * @PostApi(path="adminLogin", description="后台登录")
     * @Query(key="account|账号", rule="optional")
     * @Query(key="password|密码", rule="optional")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":1})
     */
    public function adminLogin(Jwt $jwt){
        $account = $this->request->input('account');
        if (!$account) return fail("Le compte n'est pas rempli");
        $password = $this->request->input('password');
        if (!$password) return fail("Le mot de passe n'est pas rempli");
        $password = md5($password);
        $res = Adminuser::getInstance()->adminLogin($account,$password);

        if (!empty($res)) {
            $info['finallytime'] = time();
            Adminuser::getInstance()->where('id',$res['id'])->update($info);
            //这里应为没有做auth的登录认证系统，为了展示随便写点数据
            $userData = [
                'uid' => $res['id'],
                'username' => $res['username'],
            ];
            //获取Token
            $token = (string)$jwt->getToken($userData);
            //返回响应的json数据
            return success(['token' => $token,'user' => $userData]);
        }
        return fail('Échec de la connexion, mot de passe ou erreur de compte');
    }

    /**
     * @param Jwt $jwt
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * User: liuan
     * Date: 2020/7/9 21:50
     * @PostApi(path="adminLogout", description="后台退出")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function adminLogout(Jwt $jwt){
        if ((string)$jwt->logout()) {
            return success(['url' => '/adminLogin','success' => '退出成功']);
        };
        return fail('Échec de la déconnexion');
    }

    public function addlist(){
        $add = $this->request->input('add');

        $list = json_decode($add,true);

        return $list;
    }
}
