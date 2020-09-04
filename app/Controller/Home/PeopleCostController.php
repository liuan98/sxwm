<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Model\People;
use App\Model\Spread;
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
 * @ApiController(tag="前台新手引导", description="新手引导/开屏页图")
 */

class PeopleCostController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * User: liuan
     * Date: 2020/7/18 15:38
     * @PostApi(path="People", description="新手引导")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":1,"text：内容":"123235423","img：图片":"1.jpg","add_time":1594192435,"update":1594352806}})
     */
    public function People(){
        $data = objectToArray(People::getInstance()->where('id',1)->first());

        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/8/10 14:59
     * @PostApi(path="getSpread", description="开屏页图")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":1,"img：图片":"1.jpg","update":12421}})
     */
    public function getSpread(){
        $data = objectToArray(Spread::getInstance()->where('id',1)->first());
        return success($data);
    }

}
