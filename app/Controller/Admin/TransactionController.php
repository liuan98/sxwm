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
 * @ApiController(tag="后台交易金额", description="交易金额/导出")
 */
class TransactionController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * User: liuan
     * Date: 2020/7/24 18:07
     * @PostApi(path="transaction", description="交易金额")
     * * @Query(key="time|时间", rule="")
     * @Query(key="id|分页id", rule="")
     * @Query(key="status|不传为全部1当月订单2当日订单", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"money：总金额":"0.00","day：今天":"0.00","order":{"id":4,"order_number：订单号":"sxwm15954821684126983057","money：钱":"0.00","payment：1线上2线下":2,"pay_time：支付时间":1595482168}}})
     */
    public function transaction(){
        $time = intval($this->request->input('time'));

        $id = max(intval($this->request->input('id')), 0);
        $id = $id && $id > 0 ? $id : 0;
        $where[] = $id > 0 ? ['id', '<', $id] : ['id', '>', 0];

        $status = $this->request->input('status');

        $data['money'] = Confirm::getInstance()->transaction();

        $data['day'] = Confirm::getInstance()->getDay();

        if(empty($status)){
            $data['order'] = Confirm::getInstance()->getDeal($time,$where);
        }elseif ($status == 1){
            $data['order'] = Confirm::getInstance()->monthDeal($where);
        }elseif ($status == 2){
            $data['order'] = Confirm::getInstance()->dayDeal($where);
        }

        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/7/24 18:49
     * @PostApi(path="getDerive", description="导出")
     * @Query(key="id|订单id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function getDerive(){
        $data = Confirm::getInstance()->where('pay_time','>',1)
            ->select('id','order_number','money','payment','pay_time')->orderBy('id','desc')
            ->take(1000)
            ->get();

        return success($data);
    }

}
