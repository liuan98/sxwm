<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Confirm;
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
 * @ApiController(tag="后台交易管理", description="交易管理")
 */
class DealController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * User: liuan
     * Date: 2020/7/23 17:38
     * @PostApi(path="getDeal", description="交易管理")
     * @Query(key="id|仓库id", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":{"month：本月交易金额":"120.00","day：今天交易金额":0,"monthTime：本月订单数":4,"dayTime：今天订单数":0,"record：柱形图":{"first：时间":1577808000,"end：时间":1580400000,"sum：所有":0,"finish：已完成":0,"due：代发货":0,"cancel：已取消":0}}})
     */
    public function getDeal(){
        $id = $this->request->input('id');

        $data['month'] = Confirm::getInstance()->month($id);//本月交易金额

        $data['day'] = Confirm::getInstance()->day($id);//今天交易金额

        $data['monthTime'] = Confirm::getInstance()->monthTime($id);//本月订单数

        $data['dayTime'] = Confirm::getInstance()->dayTime($id);//今天订单数

        $startTime = date("Y",time())."-1"."-1";

        for($i = 0;$i < 12; ++$i)
        {
            $t = strtotime("$startTime $i month");
            $last[$i]['first'] = strtotime(date('Y-m-01',$t));
            $last[$i]['end'] = strtotime(date('Y-m-',$t).date('t',$t));
        }

        $data['record'] = Confirm::getInstance()->record($last,$id);

        return success($data);

    }
}
