<?php

declare(strict_types=1);

namespace App\Middleware\Auth;

use App\Controller\IndexController;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;

class BackMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;

    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 根据具体业务判断逻辑走向，这里假设用户携带的token有效
        $token = $this->request->input('token');
        if(!$token){
            return $this->response->json(
                [
                    'code' => -1,
                    'data' => [
                        'error' => 'token不为空',
                    ],
                ]
            );
        }
        $isValidToken = make(IndexController::class)->getToken($token);
        if ($isValidToken) {
            //心跳刷新缓存
            $redis = ApplicationContext::getContainer()->get(\Redis::class);
            //获取所有的客户端id
            $fdList = $redis->sMembers(config('app_name').'_backsjd_1');
            //如果当前客户端在客户端集合中,就刷新
            if (in_array($token, $fdList)) {
                $redis->sAdd(config('app_name').'_backsjd_1', $token);
                $redis->expire(config('app_name').'_backsjd_1', 1800);
                //$redis->ttl('websocket_sjd_1');  // 返回剩余有效期值
                //$redis->persist('websocket_sjd_1');//设置永久存储
                return $handler->handle($request);
            }
        }
        return $this->response->json(
            [
                'code' => -2,
                'data' => [
                    'error' => 'token失效',
                ],
            ]
        );
    }
}