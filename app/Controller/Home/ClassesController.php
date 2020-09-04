<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Model\Broad;
use App\Model\Commodity;
use App\Model\Evaluate;
use App\Model\Subclass;
use App\Model\Warehouse;
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
 * @ApiController(tag="前台商品分类", description="大类列表/小类列表/商品列表/评价列表")
 */

class ClassesController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/18 17:51
     * @PostApi(path="Large", description="大类列表")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":3,"name：名称":"服装","sort：排序":"2","img：图片":"7.jpg","add_time":1594368555,"update":null}})
     */
    public function Large(){
        $data = Broad::getInstance()->orderBy('id','desc')->get();

        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/7/18 17:53
     * @PostApi(path="Little", description="小类列表")
     * @Query(key="id|大类id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":2,"big_id：大类id":3,"sort_little：排序":"2","name_little：名称":"衣服","add_time":1594369731,"update":null}})
     */
    public function Little(){
        $id = $this->request->input('id');
        if (!$id) return fail("L'identifiant de la catégorie principale n'est pas renseigné");

        $data = Subclass::getInstance()->where('big_id',$id)->orderBy('id','desc')->get();

        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/7/18 18:00
     * @PostApi(path="GoodsList", description="商品列表")
     * @Query(key="big_id|大类id", rule="required")
     * @Query(key="location|位置", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":2,"big_id：大类id":3,"sort_little：编号":"2","name_little：名称":"衣服","add_time":1594369731,"update":null,"list":{"id":24,"name_little：类名":"衣服","big_id：大类id":3,"little_id :小类id":2,"name：商品名":"幼儿|棉质插肩袖徽标LOGO圆领短袖T恤1111","title：标题":"","number：编号":"","money：展示价格":"10.00","price：市场价格":"0.00","standard：规格":"","attribute":2,"restrict：999999999为不限制":20,"label：标签":"","img：图片":"1.jpg","text：内容":"","status：1=>上架；2=>下架":1,"add_time":1594610633,"update":1594811344}}})
     */
    public function GoodsList(){
//        $little_id = $this->request->input('little_id');
//        if (!$little_id) return fail('小类id未填写');

        $big_id = $this->request->input('big_id');
        if (!$big_id) return fail("L'identifiant de la catégorie principale n'est pas renseigné");

        $list = Subclass::getInstance()->where('big_id',$big_id)->orderBy('id','desc')->get();

        $location = $this->request->input('location');
        if (!$location) return fail('Veuillez saisir votre emplacement');

//        $url = $location;
//
//        $lists = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$url.'&key=AIzaSyBZxAbfgeDc2z6YUOaBs8b0NuQgm_cHLdw&language=pt-br';
//
//        $lists = PostRequest($lists,'');
//
//        $location = $lists['results'][0]['address_components'][1]['long_name'];

        $location = 'Cocody';

        $new = Warehouse::getInstance()->location($location);
        if(empty($new['id'])){
            return fail('Aucun entrepôt dans la région! ! !');
        }

        foreach ($list as $k => &$v){
            $v['list'] = Commodity::getInstance()->GoodsNew($v,$new);
        }

        return success($list);
    }

    /**
     * User: liuan
     * Date: 2020/7/20 11:58
     * @PostApi(path="evaluate", description="评价列表")
     * @Query(key="goods_id|商品id", rule="required")
     * * @Query(key="id|分页id", rule="")
     * @Query(key="status|不传默认一条，传1全部", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"count：数量":3,"list":{"id":3,"goods_id：商品id":23,"order_id：订单id":"sxwm23523","name：名称":"评价3","phone：电话":"130","serial：编号":"23523566","text：内容":"哈哈哈哈","level：星级":"1","status：1未审核2成功3失败":3,"add_time":1594881384}}})
     */
    public function evaluate(){
        $goods_id = $this->request->input('goods_id');
        if (!$goods_id) return fail("L'identifiant du produit n'est pas renseigné");

        $status = $this->request->input('status');

        $id = max(intval($this->request->input('id')), 0);
        $id = $id && $id > 0 ? $id : 0;
        $where[] = $id > 0 ? ['id', '<', $id] : ['id', '>', 0];

        $data['count'] = Evaluate::getInstance()->count($goods_id);
        if(empty($status)){
            $num = 1;
            $data['list'] = Evaluate::getInstance()->evaluate($num,$where,$goods_id);
            return success($data);
        }else{
            $num = 20;
            $data['list'] = Evaluate::getInstance()->evaluate($num,$where,$goods_id);
            return success($data);
        }

    }


}
