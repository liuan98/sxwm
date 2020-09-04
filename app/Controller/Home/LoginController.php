<?php

declare(strict_types=1);

namespace App\Controller\Home;

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
use Phper666\JwtAuth\Jwt;

/**
 * @ApiController(tag="前台登录", description="前台登录/前台退出")
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
     * User: liuan
     * Date: 2020/7/18 15:15
     * @PostApi(path="homeLogin", description="前台登录")
     * @Query(key="phone|电话", rule="required")
     * @Query(key="password|密码", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJ","user":{"uid：用户id":10,"username：名称":"用户1072398654"}}})
     */
    public function homeLogin(Jwt $jwt){
        $phone = $this->request->input('phone');
        if (!$phone) return fail("Le téléphone n'est pas rempli");
        $pass = $this->request->input('password');
        if (!$pass) return fail("Le mot de passe n'est pas rempli");
        $password = md5($pass);

        $list = Member::getInstance()->homeLogin($phone,$password);

        if($list['status'] == 2){
            return fail("Ce compte a été désactivé! ! !");
        }

        if(!empty($list)){
            //这里应为没有做auth的登录认证系统，为了展示随便写点数据
            $userData = [
                'uid' => $list['id'],
                'username' => $list['username'],
            ];
            //获取Token
            $token = (string)$jwt->hoemToken($userData);
            //返回响应的json数据
            return success(['token' => $token,'user' => $userData]);
        }
        return fail('Échec de la connexion, mot de passe ou erreur de compte');
    }

    /**
     * @param Jwt $jwt
     * @return array
     * User: liuan
     * Date: 2020/7/18 15:17
     * @PostApi(path="homeLogout", description="前台退出")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"url：路由":"\/homeLogin","success":"退出成功"}})
     */
    public function homeLogout(Jwt $jwt){
        if ((string)$jwt->homeLogout()) {
            return success(['url' => '/homeLogin','success' => '退出成功']);
        };
        return fail('Échec de la déconnexion');
    }

}
