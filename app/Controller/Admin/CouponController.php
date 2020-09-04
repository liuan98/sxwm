<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Coupon;
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
 * @ApiController(tag="后台优惠劵管理", description="优惠劵添加/优惠卷列表/优惠劵删除")
 */


class CouponController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * @return int
     * User: liuan
     * Date: 2020/7/17 17:20
     * @PostApi(path="couponAdd", description="优惠劵添加")
     * @Query(key="name|名称", rule="required")
     * @Query(key="full|满多少", rule="")
     * @Query(key="not|无门槛", rule="required")
     * @Query(key="start|开始时间", rule="")
     * @Query(key="end|结束时间", rule="")
     * @Query(key="day|有效天数与上时间二选一", rule="")
     * @Query(key="way|发放方式，默认0", rule="")
     * @Query(key="restrict|限制", rule="")
     * @Query(key="count|数量", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function couponAdd(){
        $data = $this->request->all();

        if(empty($data['not']) && empty($data['full'])){
            return fail('Veuillez ajouter le montant de la réduction');
        }
        if(empty($data['day'])){
            if(empty($data['start']) || empty($data['end'])){
                return fail('Veuillez ajouter une date valide');
            }
        }else{
            if(empty($data['day'])){
                return fail('Veuillez ajouter une date valide');
            }
        }
        if(empty($data['count'])){
            $data['count'] = 999999999;
        }

        $data['add_time'] = time();
        $list = Coupon::getInstance()->insert($data);
        if(!empty($list)){
            return success('Ajouté avec succès');
        }else{
            return fail('ajouter a échoué');
        }

    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/17 18:17
     * @PostApi(path="couponList", description="优惠卷列表")
     * @Query(key="id|分页", rule="")
     * @Query(key="status|不传默认发放中，传1为过期", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":10,"name：优惠劵名":"优惠劵3","way：发放方式":0,"money：面额":"10.00","count：数量":0,"restrict：每人可以领多少":0,"end：有效时间":"2020-07-16 12:00:07至2020-07-20 12:00:07","add_time：创建时间":"2020-07-17 06:27:03"}})
     */
    public function couponList(){
        $id = max(intval($this->request->input('id')), 0);
        $id = $id && $id > 0 ? $id : 0;
        $where[] = $id > 0 ? ['id', '<', $id] : ['id', '>', 0];

        $status = $this->request->input('status');

        if(empty($status)){
            $data = Coupon::getInstance()->couponList($where);
            $num = count($data) - 1;

            for ($i=0; $i<=$num; $i++)
            {
                $list[$i]['id'] = $data[$i]['id'];
                $list[$i]['name'] = $data[$i]['name'];
                $list[$i]['full'] = $data[$i]['full'];
                $list[$i]['not'] = $data[$i]['not'];
                $list[$i]['start'] = $data[$i]['start'];
                $list[$i]['end'] = $data[$i]['end'];
                $list[$i]['day'] = $data[$i]['day'];
                $list[$i]['way'] = $data[$i]['way'];
                $list[$i]['restrict'] = $data[$i]['restrict'];
                $list[$i]['count'] = $data[$i]['count'];
                $list[$i]['add_time'] = $data[$i]['add_time'];
                $list[$i]['update'] = $data[$i]['update'];
                if($list[$i]['end'] < time() && $list[$i]['end'] >1){
                    unset($list[$i]);
                }
            }
            foreach ($list as $k => $v){
                $new[$k]['id'] = $v['id'];
                $new[$k]['name'] = $v['name'];
                $new[$k]['way'] = $v['way'] == 0?'Recevoir un centre de bons':'inviter'.$v['way']."Réclamation d'amis";
                $new[$k]['money'] = empty($v['full'])?$v['not']:$v['full'].'-'.$v['not'];
                $new[$k]['count'] = $v['count'] == 999999999?'Illimité':$v['count'];
                $new[$k]['restrict'] = $v['restrict'];
                $new[$k]['end'] = empty($v['end'])?'Période de validité'.$v['day'].'journée':date('Y-m-d h:i:s',$v['start']).'à'.date('Y-m-d h:i:s',$v['end']);
                $new[$k]['add_time'] = date('Y-m-d h:i:s',$v['add_time']);
            }
            return success($new);
        }else{
            $data = Coupon::getInstance()->coupon($where);

            $num = count($data) - 1;

            for ($i=0; $i<=$num; $i++)
            {
                $list[$i]['id'] = $data[$i]['id'];
                $list[$i]['name'] = $data[$i]['name'];
                $list[$i]['full'] = $data[$i]['full'];
                $list[$i]['not'] = $data[$i]['not'];
                $list[$i]['start'] = $data[$i]['start'];
                $list[$i]['end'] = $data[$i]['end'];
                $list[$i]['day'] = $data[$i]['day'];
                $list[$i]['way'] = $data[$i]['way'];
                $list[$i]['restrict'] = $data[$i]['restrict'];
                $list[$i]['count'] = $data[$i]['count'];
                $list[$i]['add_time'] = $data[$i]['add_time'];
                $list[$i]['update'] = $data[$i]['update'];
                if($list[$i]['end'] > time() && $list[$i]['count'] > 0){
                    unset($list[$i]);
                }
            }
            foreach ($list as $k => $v){
                $new[$k]['id'] = $v['id'];
                $new[$k]['name'] = $v['name'];
                $new[$k]['way'] = $v['way'] == 0?'Recevoir un centre de bons':'inviter'.$v['way']."Réclamation d'amis";
                $new[$k]['money'] = empty($v['full'])?$v['not']:$v['full'].'-'.$v['not'];
                $new[$k]['count'] = $v['count'] == 999999999?'Illimité':$v['count'];
                $new[$k]['restrict'] = $v['restrict'];
                $new[$k]['end'] = empty($v['end'])?'Période de validité'.$v['day'].'journée':date('Y-m-d h:i:s',$v['start']).'à'.date('Y-m-d h:i:s',$v['end']);
                $new[$k]['add_time'] = date('Y-m-d h:i:s',$v['add_time']);
            }
            return success($new);
        }

    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/18 11:50
     * @PostApi(path="couponDelete", description="优惠劵删除")
     * @Query(key="id|id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function couponDelete(){
        $id = $this->request->input('id');
        if (!$id) return fail('identifiant non rempli');

        $data = Coupon::getInstance()->where('id',$id)->delete();

        if(!empty($data)){
            return success('supprimé avec succès');
        }else{
            return fail('échec de la suppression');
        }
    }

}
