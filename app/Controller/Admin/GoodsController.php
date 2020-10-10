<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Commodity;
use App\Model\Confirm;
use App\Model\Contact;
use App\Model\Order;
use App\Model\Repertory;
use App\Model\Running;
use Hyperf\DbConnection\Db;
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

/**
 * @ApiController(tag="后台商品管理", description="商品添加/修改/商品删除/商品列表/库存盘点/谷歌翻译/商品销售统计/属性修改/上架下架")
 */

class GoodsController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/11 17:58
     * @PostApi(path="goodsAdd", description="商品添加")
     * @Query(key="big_id|大类id", rule="required")
     * @Query(key="little_id|小类id", rule="required")
     * @Query(key="name|名称", rule="required")
     * @Query(key="title|标题", rule="")
     * @Query(key="number|编号", rule="")
     * @Query(key="money|展示价格", rule="required")
     * @Query(key="price|市场价格", rule="")
     * @Query(key="standard|规格", rule="")
     * @Query(key="label|标签", rule="")
     * @Query(key="img|图片", rule="required")
     * @Query(key="text|内容", rule="")
     * @Query(key="status|1=>上架；2=>下架", rule="")
     * @Query(key="repertory|仓库库存，传数组;warehouse_id为仓库id，num为数量", rule="required")
     * @Query(key="restrict|限购数量", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":1})
     */
    public function goodsAdd(){
        $info = $this->request->all();

        if (!$info['big_id']) return fail('大类未填写');
        if (!$info['little_id']) return fail('小类未填写');
        if (!$info['name']) return fail('名称未填写');
        if (!$info['money']) return fail('展示价格未填写');
        if (!$info['img']) return fail('图片未填写');

        $info['add_time'] = time();
        $gitName = objectToArray(Commodity::getInstance()->where('name',$info['name'])->first());
        if(!empty($gitName)){
            return fail('添加失败,该商品已存在');
        }
        $arr = '1234567890';
        if(empty($info['number'])){
            $info['number'] = 'sxwm'.str_shuffle($arr);
        }else{
            $number = Commodity::getInstance()->where('number',$info['number'])->value('number');
            if(!empty($number)){
                return fail('编号重复，添加失败');
            }
        }
        $list = $info['repertory'];
        if(empty($info['restrict'])){
            $info['restrict'] = '999999999';
        }
        unset($info['repertory']);
        Db::beginTransaction();
        try{
            $data = Commodity::getInstance()->create($info);
            $name = json_decode($list, true);
            foreach ($name as $k => $v){
                $new[$k]['good_id'] = $data['id'];
                $new[$k]['warehouse_id'] = $v['warehouse_id'];
                $new[$k]['num'] = $v['num'];
                $new[$k]['add_time'] = time();

                $app[$k]['good_id'] = $data['id'];
                $app[$k]['warehouse_id'] = $v['warehouse_id'];
                $app[$k]['num'] = $v['num'];
                $app[$k]['status'] = 1;
                $app[$k]['operation'] = "后台增加";
                $app[$k]['add_time'] = time();
            }
            Repertory::getInstance()->insert($new);//添加到库存
            Running::getInstance()->insert($app);//添加到记录表
            Db::commit();
        } catch(\Throwable $ex){
            Db::rollBack();
            return fail('添加失败');
        }
        return success('添加成功');
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/15 17:03
     * @PostApi(path="goodsUpdate", description="商品修改")
     * @Query(key="id|商品id", rule="required")
     * @Query(key="big_id|大类id", rule="required")
     * @Query(key="little_id|小类id", rule="required")
     * @Query(key="name|名称", rule="required")
     * @Query(key="title|标题", rule="")
     * @Query(key="number|编号", rule="")
     * @Query(key="money|展示价格", rule="required")
     * @Query(key="price|市场价格", rule="")
     * @Query(key="standard|规格", rule="")
     * @Query(key="label|标签", rule="")
     * @Query(key="img|图片", rule="required")
     * @Query(key="text|内容", rule="")
     * @Query(key="status|1=>上架；2=>下架", rule="")
     * @Query(key="repertory|仓库库存，传数组;id为库存id，warehouse_id为仓库id，num为数量", rule="required")
     * @Query(key="restrict|限购数量", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="添加成功", schema={"data":1})
     */
    public function goodsUpdate(){
        $info = $this->request->all();

        if (!$info['id']) return fail('identifiant non rempli');
        if (!$info['big_id']) return fail("La catégorie principale n'est pas remplie");
        if (!$info['little_id']) return fail("La sous-catégorie n'est pas remplie");
        if (!$info['name']) return fail("Le nom n'est pas renseigné");
        if (!$info['money']) return fail("Le prix affiché n'est pas renseigné");
        if (!$info['img']) return fail("L'image n'est pas remplie");

        if(empty($info['restrict'])){
            $info['restrict'] = '999999999';
        }

        $number = Commodity::getInstance()->where('number',$info['number'])->value('number');
        if(!empty($number)){
            return fail('编号重复，添加失败');
        }

        $info['update'] = time();
        $list = $info['repertory'];

        unset($info['repertory']);
        Db::beginTransaction();
        try{
            Commodity::getInstance()->where('id',$info['id'])->update($info);

            $name = json_decode($list, true);
//            return $name;
            foreach ($name as $k => $v){
                if(!empty($v['id'])){
                    $new[] = $v;
                }else{
                    $app[] = $v;
                }
            }
            foreach ($new as $k => $v){
                $goods[$k] = objectToArray(Repertory::getInstance()->where('id',$v['id'])->first());
            }
            foreach ($new as $k => $v){
                $v['update'] = time();
                Repertory::getInstance()->where('id',$v['id'])->update($v);
            }
//            var_dump($goods);
            $num = count($goods) -1;
            for ($i=0; $i<=$num; $i++)
            {
                $html[$i]['good_id'] = $goods[$i]['good_id'];
                $html[$i]['warehouse_id'] = $goods[$i]['warehouse_id'];
                $html[$i]['numder'] = $new[$i]['num'] - $goods[$i]['num'];
                $html[$i]['status'] = $html[$i]['numder']>0?'1':'2';
                $html[$i]['operation'] = $html[$i]['status'] == 1?'Augmentation du fond':"Réduction de l'arrière-plan";
                $html[$i]['add_time'] = time();
                $html[$i]['num'] = abs($html[$i]['numder']);
                unset($html[$i]['numder']);
                if($html[$i]['num'] == 0){
                    unset($html[$i]);
                }
            }
//            var_dump($html);die;
            Running::getInstance()->insert($html);//添加到记录表
//            var_dump($app,__LINE__);
            if(!empty($app)) {
                foreach ($app as $k => $v) {
                    $newList[$k]['good_id'] = $info['id'];
                    $newList[$k]['warehouse_id'] = $v['warehouse_id'];
                    $newList[$k]['num'] = $v['num'];
                    $newList[$k]['add_time'] = time();

                    $appList[$k]['good_id'] = $info['id'];
                    $appList[$k]['warehouse_id'] = $v['warehouse_id'];
                    $appList[$k]['num'] = $v['num'];
                    $appList[$k]['status'] = 1;
                    $appList[$k]['operation'] = "Augmentation du fond";
                    $appList[$k]['add_time'] = time();
                }
//                var_dump($newList, __LINE__);
//                var_dump($appList, __LINE__);
                Repertory::getInstance()->insert($newList);//添加到库存
                Running::getInstance()->insert($appList);//添加到记录表
            }
            Db::commit();
        } catch(\Throwable $ex){
            Db::rollBack();
            return fail('échec de modifier');
        }
        return success('Modifié avec succès');
    }

    /**
     * @return string
     * User: liuan
     * Date: 2020/7/13 16:17
     * @PostApi(path="Google", description="谷歌翻译")
     * @Query(key="text|文本", rule="required")
     * @Query(key="en|语言代码缩写；法语：fr", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function Google(){
        $text = $this->request->input('text');
        $en = $this->request->input('en');
        $data = getGoogle($text,$en);
        return success($data);
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/15 17:26
     * @PostApi(path="goodsDelete", description="商品删除")
     * @Query(key="id|多条1,2,3这样传", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function goodsDelete(){
        $id = $this->request->input('id');
        if (!$id) return fail('identifiant non rempli');

        $list = explode(',',$id);

        $data = Commodity::getInstance()->whereIn('id',$list)->delete();

        if(!empty($data)){
            return success('supprimé avec succès');
        }else{
            return fail('échec de la suppression');
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/15 17:29
     * @PostApi(path="goodsList", description="商品列表")
     * @Query(key="id|分页", rule="")
     * @Query(key="name|搜索", rule="")
     * @Query(key="time|时间", rule="")
     * @Query(key="little_id|小类id", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":26,"big_id：大类id":3,"little_id：小类id":3,"name：名称":"测试天地","title：标题":"","number：编号":"65465634","money：展示价格":"100.00","price：市场价格":"232.00","standard：规格":"箱","attribute：0=>没有；1=>划算；2=>推荐":0,"label：标签":"","img：图片":"贵绳股份.jpg","text：内容":"","status：1=>上架；2=>下架":0,"add_time：添加时间":1597644110,"update":null,"warehouse：仓库":{"id：库存id":24,"warehouse_id：仓库id":4,"name：仓库名":"仓库4","num：库存":"111"}}})
     */
    public function goodsList(){
        $id = max(intval($this->request->input('id')), 0);
        $name = $this->request->input('name');
        $little_id = $this->request->input('little_id');
        $time = intval($this->request->input('time'));

        $id = $id && $id > 0 ? $id : 0;
        $where[] = $id > 0 ? ['id', '<', $id] : ['id', '>', 0];
        $where[] = ['name', 'like', '%'.$name.'%'];

        $data = Commodity::getInstance()->goodsList($where,$little_id,$time);

        return success($data);
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/16 9:31
     * @PostApi(path="operation", description="库存盘点")
     * @Query(key="id|id", rule="")
     * @Query(key="name|name", rule="")
     * @Query(key="time|时间", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":17,"number：编号":"","name：名称":"幼儿|棉质插肩袖徽标LOGO圆领短袖T恤1111","warehouse：仓库":"仓库4","numTwo：库存数量":"300","status：1增加2减少":1,"num：数量":"300","operation：什么地方操作的":"后台增加","add_time：时间":1594811344}})
     */
    public function operation(){
        $id = max(intval($this->request->input('id')), 0);
        $name = $this->request->input('name');
        $time = intval($this->request->input('time'));

        $id = $id && $id > 0 ? $id : 0;
        $where[] = $id > 0 ? ['r.id', '<', $id] : ['r.id', '>', 0];
        $where[] = ['c.name', 'like', '%'.$name.'%'];

        $data = Running::getInstance()->operation($where,$time);

        return success($data);
    }


    /**
     * User: liuan
     * Date: 2020/7/23 16:50
     * @PostApi(path="statistics", description="商品销售统计")
     * @Query(key="name|商品名", rule="")
     * @Query(key="time|时间", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"goods_id：商品id":23,"name：名称":"幼儿|棉质裤子","number：数量":"15"}})
     */
    public function statistics(){
        $name = $this->request->input('name');
        $time = intval($this->request->input('time'));

        $data = Order::getInstance()->statistics();
        $where[] = ['c.name', 'like', '%'.$name.'%'];
        if(!empty($name)){
            $data = Order::getInstance()->listName($where);
        }

        if(!empty($time)){
            $data = Order::getInstance()->listTime($time);
        }

        if(!empty($time) && !empty($name)){
            $data = Order::getInstance()->stati($time,$where);
        }

        return success($data);

    }


    /**
     * User: liuan
     * Date: 2020/8/7 17:55
     * @PostApi(path="getProperty", description="商品属性修改")
     * @Query(key="id|id", rule="required")
     * @Query(key="attribute|1=>划算；2=>推荐", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function getProperty(){
        $id = $this->request->input('id');
        if (!$id) return fail('identifiant non rempli');

        $list['attribute'] = $this->request->input('attribute');
        if (!$list['attribute']) return fail('Recommandé non rempli');

        $data = Commodity::getInstance()->where('id',$id)->update($list);

        if(!empty($data)){
            return success('Succès');
        }else{
            return fail('échec');
        }
    }

    /**
     * User: liuan
     * Date: 2020/8/8 14:27
     * @PostApi(path="getSold", description="上架下架")
     * @Query(key="id|id", rule="required")
     * @Query(key="status|1=>上架；2=>下架", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function getSold(){
        $id = $this->request->input('id');
        if (!$id) return fail('identifiant non rempli');

        $list['status'] = $this->request->input('status');
        if (!$list['status']) return fail("Le statut n'est pas rempli");

        $data = Commodity::getInstance()->where('id',$id)->update($list);

        if(!empty($data)){
            return success('Succès');
        }else{
            return fail('échec');
        }
    }

}
