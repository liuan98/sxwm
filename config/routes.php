<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@dist');//前台
Router::addRoute(['GET', 'POST', 'HEAD'], '/back', 'App\Controller\IndexController@back');//后台
Router::addRoute(['GET','POST', 'HEAD'], '/upload', 'App\Controller\UploadController@upload');//上传图片

Router::addRoute(['GET', 'POST', 'HEAD'], '/adminLogin', 'App\Controller\Admin\LoginController@adminLogin');//后台登录

Router::addGroup('/admin', function () {
    Router::addRoute(['GET', 'POST', 'HEAD'], '/adminLogout', 'App\Controller\Admin\LoginController@adminLogout');//后台退出

    Router::addRoute(['GET', 'POST', 'HEAD'], '/people', 'App\Controller\Admin\AdminController@people');//新人引导展示/修改
    Router::addRoute(['GET', 'POST', 'HEAD'], '/System', 'App\Controller\Admin\AdminController@System');//系统设置展示/修改
    Router::addRoute(['GET', 'POST', 'HEAD'], '/getPage', 'App\Controller\Admin\AdminController@getPage');//首页

    Router::addRoute(['GET', 'POST', 'HEAD'], '/warehouseAdd', 'App\Controller\Admin\WarehouseController@warehouseAdd');//仓库添加/修改
    Router::addRoute(['GET', 'POST', 'HEAD'], '/warehouseList', 'App\Controller\Admin\WarehouseController@warehouseList');//仓库列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/warehouseDelete', 'App\Controller\Admin\WarehouseController@warehouseDelete');//删除

    Router::addRoute(['GET', 'POST', 'HEAD'], '/userAdd', 'App\Controller\Admin\UserController@userAdd');//用户添加
    Router::addRoute(['GET', 'POST', 'HEAD'], '/userList', 'App\Controller\Admin\UserController@userList');//用户列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/userDelete', 'App\Controller\Admin\UserController@userDelete');//删除
    Router::addRoute(['GET', 'POST', 'HEAD'], '/Auth', 'App\Controller\Admin\UserController@Auth');//权限列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/userAuth', 'App\Controller\Admin\UserController@userAuth');//用户权限

    Router::addRoute(['GET', 'POST', 'HEAD'], '/largeAdd', 'App\Controller\Admin\ClassifyController@largeAdd');//大类添加/修改
    Router::addRoute(['GET', 'POST', 'HEAD'], '/largeList', 'App\Controller\Admin\ClassifyController@largeList');//大类列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/largeDelete', 'App\Controller\Admin\ClassifyController@largeDelete');//大类删除

    Router::addRoute(['GET', 'POST', 'HEAD'], '/subclassAdd', 'App\Controller\Admin\ClassifyController@subclassAdd');//小类添加/修改
    Router::addRoute(['GET', 'POST', 'HEAD'], '/subclassList', 'App\Controller\Admin\ClassifyController@subclassList');//小类列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/subclassDelete', 'App\Controller\Admin\ClassifyController@subclassDelete');//小类删除

    Router::addRoute(['GET', 'POST', 'HEAD'], '/goodsAdd', 'App\Controller\Admin\GoodsController@goodsAdd');//商品添加
    Router::addRoute(['GET', 'POST', 'HEAD'], '/goodsUpdate', 'App\Controller\Admin\GoodsController@goodsUpdate');//商品修改
    Router::addRoute(['GET', 'POST', 'HEAD'], '/goodsDelete', 'App\Controller\Admin\GoodsController@goodsDelete');//商品删除
    Router::addRoute(['GET', 'POST', 'HEAD'], '/goodsList', 'App\Controller\Admin\GoodsController@goodsList');//商品列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/operation', 'App\Controller\Admin\GoodsController@operation');//库存盘点
    Router::addRoute(['GET', 'POST', 'HEAD'], '/statistics', 'App\Controller\Admin\GoodsController@statistics');//商品销售统计
    Router::addRoute(['GET', 'POST', 'HEAD'], '/getProperty', 'App\Controller\Admin\GoodsController@getProperty');//商品属性修改
    Router::addRoute(['GET', 'POST', 'HEAD'], '/getSold', 'App\Controller\Admin\GoodsController@getSold');//上架下架

    Router::addRoute(['GET', 'POST', 'HEAD'], '/imgAdd', 'App\Controller\Admin\ImgController@imgAdd');//图片添加修改
    Router::addRoute(['GET', 'POST', 'HEAD'], '/imgList', 'App\Controller\Admin\ImgController@imgList');//图片列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/imgDelete', 'App\Controller\Admin\ImgController@imgDelete');//图片删除
    Router::addRoute(['GET', 'POST', 'HEAD'], '/imgStatus', 'App\Controller\Admin\ImgController@imgStatus');//图片状态修改

    Router::addRoute(['GET', 'POST', 'HEAD'], '/functionAdd', 'App\Controller\Admin\ImgController@functionAdd');//功能按钮添加修改
    Router::addRoute(['GET', 'POST', 'HEAD'], '/functionList', 'App\Controller\Admin\ImgController@functionList');//功能按钮列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/functionDelete', 'App\Controller\Admin\ImgController@functionDelete');//功能按钮删除
    Router::addRoute(['GET', 'POST', 'HEAD'], '/functionStatus', 'App\Controller\Admin\ImgController@functionStatus');//功能按钮状态修改

    Router::addRoute(['GET', 'POST', 'HEAD'], '/alterSpread', 'App\Controller\Admin\ImgController@alterSpread');//开屏页修改
    Router::addRoute(['GET', 'POST', 'HEAD'], '/spread', 'App\Controller\Admin\ImgController@spread');//开屏页

    Router::addRoute(['GET', 'POST', 'HEAD'], '/memberList', 'App\Controller\Admin\MemberController@memberList');//会员列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/memberUpdate', 'App\Controller\Admin\MemberController@memberUpdate');//会员修改
    Router::addRoute(['GET', 'POST', 'HEAD'], '/memberDelete', 'App\Controller\Admin\MemberController@memberDelete');//会员删除
    Router::addRoute(['GET', 'POST', 'HEAD'], '/memberStatus', 'App\Controller\Admin\MemberController@memberStatus');//会员状态修改

    Router::addRoute(['GET', 'POST', 'HEAD'], '/evaluateList', 'App\Controller\Admin\EvaluateController@evaluateList');//评价列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/evaluateUpdate', 'App\Controller\Admin\EvaluateController@evaluateUpdate');//评价状态修改
    Router::addRoute(['GET', 'POST', 'HEAD'], '/evaluateDelete', 'App\Controller\Admin\EvaluateController@evaluateDelete');//评价状态删除

    Router::addRoute(['GET', 'POST', 'HEAD'], '/appList', 'App\Controller\Admin\EvaluateController@appList');//app评价列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/appDelete', 'App\Controller\Admin\EvaluateController@appDelete');//app评价删除

    Router::addRoute(['GET', 'POST', 'HEAD'], '/suggestList', 'App\Controller\Admin\SuggestController@suggestList');//建议列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/suggestStatus', 'App\Controller\Admin\SuggestController@suggestStatus');//建议状态修改
    Router::addRoute(['GET', 'POST', 'HEAD'], '/suggestDelete', 'App\Controller\Admin\SuggestController@suggestDelete');//建议状态删除

    Router::addRoute(['GET', 'POST', 'HEAD'], '/afterList', 'App\Controller\Admin\AfterController@afterList');//售后服务列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/afterStatus', 'App\Controller\Admin\AfterController@afterStatus');//售后服务状态修改
    Router::addRoute(['GET', 'POST', 'HEAD'], '/afterDelete', 'App\Controller\Admin\AfterController@afterDelete');//售后服务删除

    Router::addRoute(['GET', 'POST', 'HEAD'], '/complainList', 'App\Controller\Admin\AfterController@complainList');//投诉列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/complainStatus', 'App\Controller\Admin\AfterController@complainStatus');//投诉状态修改
    Router::addRoute(['GET', 'POST', 'HEAD'], '/complainDelete', 'App\Controller\Admin\AfterController@complainDelete');//投诉删除

    Router::addRoute(['GET', 'POST', 'HEAD'], '/shoppingAdd', 'App\Controller\Admin\ShoppingController@shoppingAdd');//购物卡添加
    Router::addRoute(['GET', 'POST', 'HEAD'], '/shoppingList', 'App\Controller\Admin\ShoppingController@shoppingList');//购物卡列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/shoppingDelete', 'App\Controller\Admin\ShoppingController@shoppingDelete');//购物卡删除
    Router::addRoute(['GET', 'POST', 'HEAD'], '/shoppingExport', 'App\Controller\Admin\ShoppingController@shoppingExport');//购物卡导出

    Router::addRoute(['GET', 'POST', 'HEAD'], '/inviteList', 'App\Controller\Admin\InviteController@inviteList');//邀请规则列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/inviteUpdate', 'App\Controller\Admin\InviteController@inviteUpdate');//邀请规则修改
    Router::addRoute(['GET', 'POST', 'HEAD'], '/InvitedRecord', 'App\Controller\Admin\InviteController@InvitedRecord');//邀请记录
    Router::addRoute(['GET', 'POST', 'HEAD'], '/invitedDelete', 'App\Controller\Admin\InviteController@invitedDelete');//邀请记录删除

    Router::addRoute(['GET', 'POST', 'HEAD'], '/couponAdd', 'App\Controller\Admin\CouponController@couponAdd');//优惠卷添加
    Router::addRoute(['GET', 'POST', 'HEAD'], '/couponList', 'App\Controller\Admin\CouponController@couponList');//优惠卷列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/couponDelete', 'App\Controller\Admin\CouponController@couponDelete');//优惠卷删除

    Router::addRoute(['GET', 'POST', 'HEAD'], '/aboutList', 'App\Controller\Admin\ArticleController@aboutList');//关于我们展示/修改

    Router::addRoute(['GET', 'POST', 'HEAD'], '/contactAdd', 'App\Controller\Admin\ArticleController@contactAdd');//联系我们添加/修改
    Router::addRoute(['GET', 'POST', 'HEAD'], '/contactList', 'App\Controller\Admin\ArticleController@contactList');//列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/contactDelete', 'App\Controller\Admin\ArticleController@contactDelete');//删除

    Router::addRoute(['GET', 'POST', 'HEAD'], '/getDeal', 'App\Controller\Admin\DealController@getDeal');//交易管理

    Router::addRoute(['GET', 'POST', 'HEAD'], '/orderList', 'App\Controller\Admin\OrderController@orderList');//订单列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/orderCancel', 'App\Controller\Admin\OrderController@orderCancel');//发货完成按钮
    Router::addRoute(['GET', 'POST', 'HEAD'], '/getCancel', 'App\Controller\Admin\OrderController@getCancel');//取消订单跟线上退款可共用
    Router::addRoute(['GET', 'POST', 'HEAD'], '/getRefund', 'App\Controller\Admin\OrderController@getRefund');//取消退款
    Router::addRoute(['GET', 'POST', 'HEAD'], '/orderDelete', 'App\Controller\Admin\OrderController@orderDelete');//删除订单
    Router::addRoute(['GET', 'POST', 'HEAD'], '/orderDetails', 'App\Controller\Admin\OrderController@orderDetails');//订单详情

    Router::addRoute(['GET', 'POST', 'HEAD'], '/transaction', 'App\Controller\Admin\TransactionController@transaction');//交易金额
    Router::addRoute(['GET', 'POST', 'HEAD'], '/getDerive', 'App\Controller\Admin\TransactionController@getDerive');//导出

    Router::addRoute(['GET', 'POST', 'HEAD'], '/listFreight', 'App\Controller\Admin\GetFreightController@listFreight');//运费展示
    Router::addRoute(['GET', 'POST', 'HEAD'], '/delFreight', 'App\Controller\Admin\GetFreightController@delFreight');//运费修改

}, ['middleware' => [Phper666\JwtAuth\Middleware\JwtAuthMiddleware::class]]);
Router::addRoute(['GET', 'POST', 'HEAD'], '/Google', 'App\Controller\Admin\GoodsController@Google');//谷歌翻译




Router::addRoute(['GET', 'POST', 'HEAD'], '/Register', 'App\Controller\Home\RegisterCostController@Register');//前台注册
Router::addRoute(['GET', 'POST', 'HEAD'], '/Protocol', 'App\Controller\Home\RegisterCostController@Protocol');//隐私协议
Router::addRoute(['GET', 'POST', 'HEAD'], '/Modification', 'App\Controller\Home\RegisterCostController@Modification');//忘记密码
Router::addRoute(['GET', 'POST', 'HEAD'], '/verify', 'App\Controller\Home\RegisterCostController@verify');//验证码
Router::addRoute(['GET', 'POST', 'HEAD'], '/getSpread', 'App\Controller\Home\PeopleCostController@getSpread');//开屏页图
Router::addRoute(['GET', 'POST', 'HEAD'], '/homeLogin', 'App\Controller\Home\LoginController@homeLogin');//前台登录
Router::addGroup('/home', function () {
    Router::addRoute(['GET', 'POST', 'HEAD'], '/homeLogout', 'App\Controller\Home\LoginController@homeLogout');//前台退出

    Router::addRoute(['GET', 'POST', 'HEAD'], '/People', 'App\Controller\Home\PeopleCostController@People');//新手引导

    Router::addRoute(['GET', 'POST', 'HEAD'], '/pageList', 'App\Controller\Home\HomepageController@pageList');//轮播图功能图
    Router::addRoute(['GET', 'POST', 'HEAD'], '/bargain', 'App\Controller\Home\HomepageController@bargain');//超划算
    Router::addRoute(['GET', 'POST', 'HEAD'], '/Recommend', 'App\Controller\Home\HomepageController@Recommend');//推荐
    Router::addRoute(['GET', 'POST', 'HEAD'], '/Particulars', 'App\Controller\Home\HomepageController@Particulars');//商品详情
    Router::addRoute(['GET', 'POST', 'HEAD'], '/Search', 'App\Controller\Home\HomepageController@Search');//搜索
    Router::addRoute(['GET', 'POST', 'HEAD'], '/HotBot', 'App\Controller\Home\HomepageController@HotBot');//热搜记录

    Router::addRoute(['GET', 'POST', 'HEAD'], '/Large', 'App\Controller\Home\ClassesController@Large');//大类列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/Little', 'App\Controller\Home\ClassesController@Little');//小类列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/GoodsList', 'App\Controller\Home\ClassesController@GoodsList');//商品列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/evaluate', 'App\Controller\Home\ClassesController@evaluate');//评价列表

    Router::addRoute(['GET', 'POST', 'HEAD'], '/vehicleAdd', 'App\Controller\Home\VehicleController@vehicleAdd');//添加购物车
    Router::addRoute(['GET', 'POST', 'HEAD'], '/vehicleList', 'App\Controller\Home\VehicleController@vehicleList');//购物车列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/vehicleUpdate', 'App\Controller\Home\VehicleController@vehicleUpdate');//购物车修改
    Router::addRoute(['GET', 'POST', 'HEAD'], '/vehicleDelete', 'App\Controller\Home\VehicleController@vehicleDelete');//购物车删除

    Router::addRoute(['GET', 'POST', 'HEAD'], '/siteAdd', 'App\Controller\Home\SiteController@siteAdd');//添加修改收货地址
    Router::addRoute(['GET', 'POST', 'HEAD'], '/siteList', 'App\Controller\Home\SiteController@siteList');//收货地址列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/siteUpdate', 'App\Controller\Home\SiteController@siteUpdate');//修改默认收货地址
    Router::addRoute(['GET', 'POST', 'HEAD'], '/siteDelete', 'App\Controller\Home\SiteController@siteDelete');//删除收货地址
    Router::addRoute(['GET', 'POST', 'HEAD'], '/default', 'App\Controller\Home\SiteController@default');//默认收货地址

    Router::addRoute(['GET', 'POST', 'HEAD'], '/workday', 'App\Controller\Home\ConfirmController@workday');//上班时间
    Router::addRoute(['GET', 'POST', 'HEAD'], '/balance', 'App\Controller\Home\ConfirmController@balance');//余额
    Router::addRoute(['GET', 'POST', 'HEAD'], '/getFreight', 'App\Controller\Home\ConfirmController@getFreight');//运费
    Router::addRoute(['GET', 'POST', 'HEAD'], '/ticket', 'App\Controller\Home\ConfirmController@ticket');//优惠券


    Router::addRoute(['GET', 'POST', 'HEAD'], '/myList', 'App\Controller\Home\PersonageController@myList');//我的首页
    Router::addRoute(['GET', 'POST', 'HEAD'], '/getMy', 'App\Controller\Home\PersonageController@getMy');//个人详情
    Router::addRoute(['GET', 'POST', 'HEAD'], '/myUpdate', 'App\Controller\Home\PersonageController@myUpdate');//修改
    Router::addRoute(['GET', 'POST', 'HEAD'], '/myPhone', 'App\Controller\Home\PersonageController@myPhone');//修改电话

    Router::addRoute(['GET', 'POST', 'HEAD'], '/getProtocol', 'App\Controller\Home\SettingController@getProtocol');//服务与隐私
    Router::addRoute(['GET', 'POST', 'HEAD'], '/getAppraise', 'App\Controller\Home\SettingController@getAppraise');//评价app

    Router::addRoute(['GET', 'POST', 'HEAD'], '/codeList', 'App\Controller\Home\WuploadController@codeList');//分享app首页
    Router::addRoute(['GET', 'POST', 'HEAD'], '/getCoupon', 'App\Controller\Home\WuploadController@getCoupon');//领取优惠劵
    Router::addRoute(['GET', 'POST', 'HEAD'], '/getRegister', 'App\Controller\Home\WuploadController@getRegister');//邀请注册


    Router::addRoute(['GET', 'POST', 'HEAD'], '/getOrder', 'App\Controller\Home\OrderListController@getOrder');//订单列表
    Router::addRoute(['GET', 'POST', 'HEAD'], '/getStay', 'App\Controller\Home\OrderListController@getStay');//待评价
    Router::addRoute(['GET', 'POST', 'HEAD'], '/StayDelete', 'App\Controller\Home\OrderListController@StayDelete');//订单删除
    Router::addRoute(['GET', 'POST', 'HEAD'], '/orderStay', 'App\Controller\Home\OrderListController@orderStay');//订单评价
    Router::addRoute(['GET', 'POST', 'HEAD'], '/stayList', 'App\Controller\Home\OrderListController@stayList');//订单详情
    Router::addRoute(['GET', 'POST', 'HEAD'], '/salesPay', 'App\Controller\Home\OrderListController@salesPay');//退款
    Router::addRoute(['GET', 'POST', 'HEAD'], '/stayPay', 'App\Controller\Home\OrderListController@stayPay');//待支付
    Router::addRoute(['GET', 'POST', 'HEAD'], '/voluntarily', 'App\Controller\Home\OrderListController@voluntarily');//24小时后自动收货
    Router::addRoute(['GET', 'POST', 'HEAD'], '/applyPay', 'App\Controller\Home\OrderListController@applyPay');//申请退款
    Router::addRoute(['GET', 'POST', 'HEAD'], '/confirmReceipt', 'App\Controller\Home\OrderListController@confirmReceipt');//确认收货

    Router::addRoute(['GET', 'POST', 'HEAD'], '/getConnection', 'App\Controller\Home\ConnectionController@getConnection');//联系我们

    Router::addRoute(['GET', 'POST', 'HEAD'], '/getAfter', 'App\Controller\Home\ServiceController@getAfter');//拍照索赔
    Router::addRoute(['GET', 'POST', 'HEAD'], '/getComplain', 'App\Controller\Home\ServiceController@getComplain');//投诉建议

    Router::addRoute(['GET', 'POST', 'HEAD'], '/getCard', 'App\Controller\Home\BalanceController@getCard');//礼品卡兑换
    Router::addRoute(['GET', 'POST', 'HEAD'], '/listCard', 'App\Controller\Home\BalanceController@listCard');//礼品卡规则
    Router::addRoute(['GET', 'POST', 'HEAD'], '/getResidue', 'App\Controller\Home\BalanceController@getResidue');//账户余额

    Router::addRoute(['GET', 'POST', 'HEAD'], '/userCoupon', 'App\Controller\Home\GetCouponController@userCoupon');//个人优惠劵
    Router::addRoute(['GET', 'POST', 'HEAD'], '/centreCoupon', 'App\Controller\Home\GetCouponController@centreCoupon');//领劵中心
    Router::addRoute(['GET', 'POST', 'HEAD'], '/getNeck', 'App\Controller\Home\GetCouponController@getNeck');//领劵中心领取优惠劵

}, ['middleware' => [Phper666\JwtAuth\Middleware\JwtAuthMiddleware::class]]);
Router::addRoute(['GET', 'POST', 'HEAD'], '/notify', 'App\Controller\Home\ConfirmController@notify');//支付回调
Router::addRoute(['GET', 'POST', 'HEAD'], '/addfff', 'App\Controller\Home\ConfirmController@addfff');
Router::addRoute(['GET', 'POST', 'HEAD'], '/confirmOrder', 'App\Controller\Home\ConfirmController@confirmOrder');//确认订单

Router::addRoute(['GET', 'POST', 'HEAD'], '/addlist', 'App\Controller\Admin\LoginController@addlist');

Router::addRoute(['GET', 'POST', 'HEAD'], '/getCode', 'App\Controller\Home\WuploadController@getCode');//下载二维码

//后面
Router::addRoute(['GET', 'POST', 'HEAD'], '/newList', 'App\Controller\Home\VehicleController@newList');//链接

