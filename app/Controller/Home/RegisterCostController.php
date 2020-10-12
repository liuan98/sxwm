<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Model\About;
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
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Illuminate\Support\Facades\Cache;

/**
 * @ApiController(tag="前台注册", description="前台注册/隐私协议/忘记密码")
 */

class RegisterCostController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/18 14:34
     * @PostApi(path="Register", description="前台注册")
     * @Query(key="phone|电话", rule="required")
     * @Query(key="mail|邮箱", rule="")
     * @Query(key="verify|验证码", rule="required")
     * @Query(key="password|密码", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function Register(){
        $data['phone'] = $this->request->input("phone");
        if (!$data['phone']) return fail("Le téléphone n'est pas rempli");
        $info = objectToArray(Member::getInstance()->where('phone',$data['phone'])->first());
        if(!empty($info)){
            return fail("Ce téléphone est déjà enregistré");
        }

        $data['mail'] = $this->request->input("mail");

        $password = $this->request->input("password");
        if (!$password) return fail("Le mot de passe n'est pas rempli");
        $data['password'] = md5($password);

        $verify = $this->request->input("verify");
        if (!$verify) return fail("Le code de vérification n'est pas renseigné");
        $redis = $this->container->get(\Hyperf\Redis\Redis::class);
        $code = $redis->get('code'.$data['phone']);
        if($code != $verify) return fail("Erreur de code de vérification");

        $arr = '1234567890';
        $data['username'] = 'utilisateur'.str_shuffle($arr);
        $data['add_time'] = time();

        $list = Member::getInstance()->insert($data);
        if(!empty($list)){
            return success('Ajouté avec succès');
        }else{
            return fail('ajouter a échoué');
        }
    }


    private function code($length){
        $key = '';
        $pattern='1234567890';
        for( $i=0; $i < $length; $i++ ) {
            $key .= $pattern[mt_rand(0, 9)];
        }
        return $key;
    }


    /**
     * @return array
     * User: liuan
     * Date: 2020/7/27 9:34
     * @PostApi(path="verify", description="验证码")
     * @Query(key="phone|电话", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function verify(){
        $phone = $this->request->input("phone");
        if (!$phone) return fail("Veuillez entrer le téléphone");
        $phone = '225'.$phone;
        $redis = $this->container->get(\Hyperf\Redis\Redis::class);
        $code = $this->code(6);

        $time = date('Y-m-d h:i:s',time());
        $param = array(
            'username' => 'Topfrais',
            'password' => 'Ci57820248',
            'sender' => 'TOPFRAIS',
            'text' => "Code de vérification du centre commercial à emporter d'aliments frais:".$code,
            'type' => 'text',
            'datetime' => $time,
        );
        $recipients = array($phone);
        $post = 'to=' . implode(';', $recipients);
        foreach ($param as $key => $val) {
            $post .= '&' . $key . '=' . rawurlencode($val);
        }
        $url = "http://africasmshub.mondialsms.net/api/api_http.php";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Connection: close"));
        $result = curl_exec($ch);
        var_dump($result,__LINE__);
        if(curl_errno($ch)) {
            $result = "cURL ERROR: " . curl_errno($ch) . " " . curl_error($ch);
        } else {
            $returnCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            switch($returnCode) {
                case 200 :
                    break;
                default :
                    $result = "HTTP ERROR: " . $returnCode;
            }
        }
        curl_close($ch);
        var_dump($result,__LINE__);
        $redis->set('code'.$phone,$code,180);
        if(!empty($result)){
            return success("Bien envoyé");
        }else{
            return success("Échec de l'envoi");
        }

    }


    /**
     * @return array
     * User: liuan
     * Date: 2020/7/18 14:45
     * @PostApi(path="Protocol", description="隐私协议")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":1,"name：名称":"隐私协议","title：标题":"baiti","text：内容":"12542363467","time":1594379107}})
     */
    public function Protocol(){
        $data = objectToArray(About::getInstance()->where('id',1)->first());

        return success($data);
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/18 14:52
     * @PostApi(path="Modification", description="忘记密码")
     * @Query(key="phone|电话", rule="required")
     * @Query(key="password|密码", rule="required")
     * @Query(key="verify|验证码", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function Modification(){
        $phone = $this->request->input("phone");
        if (!$phone) return fail("Le téléphone n'est pas rempli");
        $info = objectToArray(Member::getInstance()->where('phone',$phone)->first());
        if(empty($info)){
            return fail("Le téléphone n'est pas enregistré");
        }

        $password = $this->request->input("password");
        if (!$password) return fail("Le mot de passe n'est pas rempli");
        $data['password'] = md5($password);

        $verify = $this->request->input("verify");
        if (!$verify) return fail("Le code de vérification n'est pas renseigné");
        $redis = $this->container->get(\Hyperf\Redis\Redis::class);
        $code = $redis->get('code'.$data['phone']);
        if($code != $verify) return fail("Erreur de code de vérification");

        $data['update'] = time();

        $list = Member::getInstance()->where('phone',$phone)->update($data);
        if(!empty($list)){
            return success('Ajouté avec succès');
        }else{
            return fail('ajouter a échoué');
        }
    }


}
