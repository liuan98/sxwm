<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Freight;
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
 * @ApiController(tag="后台运费", description="运费展示/运费修改")
 */
class GetFreightController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * User: liuan
     * Date: 2020/8/26 15:47
     * @PostApi(path="listFreight", description="运费展示")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":{"id":1,"full：满多少":20,"subtract：减多少":10,"update":1597025950}})
     */
    public function listFreight(){
        $data = Freight::getInstance()->first();

        return success($data);
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/8/26 15:58
     * @PostApi(path="delFreight", description="运费修改")
     * @Query(key="full|满多少", rule="required")
     * @Query(key="subtract|减多少", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":1})
     */
    public function delFreight(){
        $list['full'] = $this->request->input('full');
        if (!$list['full']) return fail("Combien n'est pas rempli");

        $list['subtract'] = $this->request->input('subtract');
        if (!$list['subtract']) return fail("Combien n'est pas rempli");

        $list['update'] = time();
        $data = Freight::getInstance()->where('id',1)->update($list);

        if(!empty($data)){
            return success('Modifié avec succès');
        }else{
            return fail('échec de modifier');
        }
    }
}
