<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Model\Broad;
use App\Model\Button;
use App\Model\Commodity;
use App\Model\Img;
use App\Model\Search;
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
 * @ApiController(tag="前台app首页", description="轮播图功能图/超划算/推荐/商品详情/搜索/热搜记录")
 */

class HomepageController extends AbstractController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/18 16:15
     * @PostApi(path="pageList", description="轮播图功能图")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"list":{"id":1,"sort：排序":"221","name：名称":"图片1","img：图片":"1.jpg","url_name：链接名":"百度","url：链接名":"www.daidu.com","status":1,"add_time":1594870385,"update":null},"info":{"id":1,"name：名称":"描述","img：图片":"1.jpg","url：链接":"www.baidu.com","status":1,"add_time":1594884358,"update":null}}})
     */
    public function pageList(){
        $data['list'] = Img::getInstance()->pageList();

        $data['info'] = Button::getInstance()->page();
        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/7/18 16:24
     * @PostApi(path="bargain", description="超划算")
     * @Query(key="id|分页", rule="")
     * @Query(key="location|位置", rule="required")
     * @Query(key="status|传1更多，不传默认3条", rule="")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":24,"big_id：大类id":3,"little_id：小类id":2,"name：商品名称":"幼儿|棉质插肩袖徽标LOGO圆领短袖T恤1111","title：标题":"","number：编号":"","money：展示价格":"10.00","price：市场价格":"0.00","standard：规格":"","attribute：0=>没有；1=>划算；2=>推荐":2,"restrict：999999999为不限制":20,"label：标签":"","img：图片":"1.jpg","text：内容":"","status：1=>上架；2=>下架":1,"add_time":1594610633,"update":1594811344,"count":"4"}})
     */
    public function bargain(){
        $id = max(intval($this->request->input('id')), 0);
        $id = $id && $id > 0 ? $id : 0;
        $where[] = $id > 0 ? ['c.id', '<', $id] : ['c.id', '>', 0];

        $status = $this->request->input('status');

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

        $list = Warehouse::getInstance()->location($location);

        if(empty($list['id'])){
            return fail('Aucun entrepôt dans la région! ! !');
        }

        if(empty($status)){
            $data = Commodity::getInstance()->bargainNew($list);

            return success($data);
        }else{
            $data = Commodity::getInstance()->bargainList($where,$list);

            return success($data);
        }
    }

    /**
     * User: liuan
     * Date: 2020/7/18 16:41
     * @PostApi(path="Recommend", description="推荐")
     * @Query(key="id|分页", rule="")
     * @Query(key="location|位置", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":24,"big_id：大类id":3,"little_id：小类id":2,"name：商品名称":"幼儿|棉质插肩袖徽标LOGO圆领短袖T恤1111","title：标题":"","number：编号":"","money：展示价格":"10.00","price：市场价格":"0.00","standard：规格":"","attribute：0=>没有；1=>划算；2=>推荐":2,"restrict：999999999为不限制":20,"label：标签":"","img：图片":"1.jpg","text：内容":"","status：1=>上架；2=>下架":1,"add_time":1594610633,"update":1594811344,"count":"1"}})
     */
    public function Recommend(){
        $id = max(intval($this->request->input('id')), 0);
        $id = $id && $id > 0 ? $id : 0;
        $where[] = $id > 0 ? ['c.id', '<', $id] : ['c.id', '>', 0];

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

        $list = Warehouse::getInstance()->location($location);
        if(empty($list['id'])){
            return fail('Aucun entrepôt dans la région! ! !');
        }

        $data = Commodity::getInstance()->Recommend($where,$list);

        return success($data);
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/18 16:54
     * @PostApi(path="Particulars", description="商品详情")
     * @Query(key="id|商品id", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":24,"big_id：大类id":3,"little_id：小类id":2,"name：商品名称":"幼儿|棉质插肩袖徽标LOGO圆领短袖T恤1111","title：标题":"","number：编号":"","money：展示价格":"10.00","price：市场价格":"0.00","standard：规格":"","attribute：0=>没有；1=>划算；2=>推荐":2,"restrict：999999999为不限制":20,"label：标签":"","img：图片":"1.jpg","text：内容":"","status：1=>上架；2=>下架":1,"add_time":1594610633,"update":1594811344}})
     */
    public function Particulars(){
        $id = $this->request->input('id');
        if (!$id) return fail("L'identifiant du produit n'est pas renseigné");

        $data = objectToArray(Commodity::getInstance()->where('id',$id)->first());

        $data['img'] = explode(',',$data['img']);

        return success($data);
    }

    /**
     * @return array
     * User: liuan
     * Date: 2020/7/18 17:09
     * @PostApi(path="Search", description="搜索")
     * @Query(key="id|分页", rule="")
     * @Query(key="name|搜索", rule="")
     * @Query(key="location|位置", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function Search(){
        $name = $this->request->input('name');
        if (!$name) return fail('Veuillez entrer les mots clés! ! !');

        $id = max(intval($this->request->input('id')), 0);
        $id = $id && $id > 0 ? $id : 0;
        $where[] = $id > 0 ? ['c.id', '<', $id] : ['c.id', '>', 0];
        $where[] = ['c.name', 'like', '%'.$name.'%'];

        $location = $this->request->input('location');
        if (!$location) return fail('Veuillez saisir votre emplacement');

        $url = $location;

        $lists = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$url.'&key=AIzaSyBZxAbfgeDc2z6YUOaBs8b0NuQgm_cHLdw&language=pt-br';

        $lists = PostRequest($lists,'');

        $location = $lists['results'][0]['address_components'][1]['long_name'];

        $new = Warehouse::getInstance()->location($location);
        if(empty($new['id'])){
            return fail('Aucun entrepôt dans la région! ! !');
        }

        $data = Commodity::getInstance()->Search($where,$new);

        $list['name'] = $name;
        $list['add_time'] = time();
        Search::getInstance()->insert($list);

        return success($data);
    }

    /**
     * User: liuan
     * Date: 2020/7/18 17:21
     * @PostApi(path="HotBot", description="热搜记录")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":{"id":11,"name：名称":"西瓜","num：数量":7}})
     */
    public function HotBot(){
        $data = Search::getInstance()->HotBot();

        return success($data);
    }


}
