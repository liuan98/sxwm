<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Shopping;
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
 * @ApiController(tag="后台购物卡管理", description="购物卡添加/购物卡列表/购物卡删除/购物卡导出")
 */

class ShoppingController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * User: liuan
     * Date: 2020/7/17 13:46
     * @PostApi(path="shoppingAdd", description="购物卡添加")
     * @Query(key="num|数量", rule="required")
     * @Query(key="money|面额", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function shoppingAdd(){
        $num = $this->request->input("num");
        if (!$num) return fail("La quantité n'est pas remplie");
        $money = $this->request->input("money");
        if (!$money) return fail("Le montant n'est pas rempli");

        $arr = '1234567890';
        $str="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";

        for ($i=1; $i<=$num; $i++)
        {
            $data[$i]['card'] = str_shuffle($arr);
            $data[$i]['password'] = substr(str_shuffle($str),26,10);
            $data[$i]['money'] = $money;
            $data[$i]['add_time'] = time();
        }

        Db::beginTransaction();
        try{

            Shopping::getInstance()->insert($data);

            Db::commit();
        } catch(\Throwable $ex){
            Db::rollBack();
            return success('ajouter a échoué');
        }
        return success('Ajouté avec succès');
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/17 14:41
     * @PostApi(path="shoppingList", description="购物卡列表")
     * @Query(key="id|分页", rule="")
     * @Query(key="status|给1已兑换", rule="")
     * @Query(key="time|时间", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":12100,"card：卡号":"4102863759","password：密码":"ukycAYHTL7","money：面额":"20.00","name：名称":null,"account：账号":null,"add_time：生成时间":1594967313,"update：兑换时间":1111111}})
     */
    public function shoppingList(){
        $id = max(intval($this->request->input('id')), 0);
        $id = $id && $id > 0 ? $id : 0;
        $where[] = $id > 0 ? ['id', '<', $id] : ['id', '>', 0];

        $status = $this->request->input('status');

        $time = intval($this->request->input("time"));

        $data = Shopping::getInstance()->shoppingList($time,$where,$status);

        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/7/17 14:48
     * @PostApi(path="shoppingDelete", description="购物卡删除")
     * @Query(key="id|id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function shoppingDelete(){
        $id = $this->request->input("id");
        if (!$id) return fail('identifiant non rempli');

        $list = Shopping::getInstance()->where('id',$id)->delete();
        if(!empty($list)){
            return success('supprimé avec succès');
        }else{
            return fail('échec de la suppression');
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/17 14:57
     * @PostApi(path="shoppingExport", description="购物卡导出")
     * @Query(key="status|给1已兑换", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function shoppingExport(){
        $status = $this->request->input('status');

        $data = Shopping::getInstance()->shoppingExport($status);

        return success($data);
    }

}
