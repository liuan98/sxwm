<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Model\Contact;
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
 * @ApiController(tag="前台联系我们", description="联系我们")
 */
class ConnectionController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }


    /**
     * User: liuan
     * Date: 2020/7/23 9:37
     * @PostApi(path="getConnection", description="联系我们")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":1,"name：名称":"la","title：标题":"baiti","text：内容":"12542363467","time":1594380347}})
     */
    public function getConnection(){
        $data = Contact::getInstance()->get();

        return success($data);
    }


}
