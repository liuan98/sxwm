<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 *
 * 文件上传
 */

namespace App\Controller;

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
 * @ApiController(tag="图片上传", description="图片上传")
 */
class UploadController extends AbstractController
{
    /**
     * 文件上传
     */
    /**
     * @return array
     * User: liuan
     * Date: 2020/7/10 10:23
     * @PostApi(path="upload", description="图片上传")
     * @Query(key="file|file", rule="required")
     * @ApiResponse(code="-1", description="参数错误")
     * @ApiResponse(code="200", description="成功", schema={"data":1})
     */
    public function upload(){
        $file = $this->request->file('file');

        if ($file->isValid()) {
            // 该路径为上传文件的临时路径
            $path = $file->getPath();

            // 由于 Swoole 上传文件的 tmp_name 并没有保持文件原名，所以这个方法已重写为获取原文件名的后缀名
            $extension = $file->getExtension();
            $size = $file->getSize();
            //file_put_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . "./size.txt", date("Y-m-d H:i:s") . "  " . var_export($size,true) . "\r\n", FILE_APPEND);
            $zkb = formatBytes($size);
            if((float)$zkb >= 2048) return fail('文件大小不得超过2M');

            //判断目录是否存在
            $lujing = BASE_PATH . '/public/static';
            if(!is_dir($lujing)){
                mkdir(iconv("UTF-8", "GBK", $lujing),0777,true);
            }

            // 上传文件
            $filename = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $extension;
            $file->moveTo(BASE_PATH . '/public/static/'.$filename);

            // 通过 isMoved(): bool 方法判断方法是否已移动
            if ($file->isMoved()) {
                return success(['path' => str_replace($this->request->getRequestTarget(),'',$this->request->getUri()) . '/static/'.$filename]);
            }
        }else{
            return fail('缺少参数');
        }
    }

    /**
     * 多文件上传
     */
    public function mulUpload(){
        $files = $this->request->file('file');
        $url = array();
        $i = 0;
        foreach ($files as $k => $file) {
            // 文件是否上传成功
            if ($file->isValid()) {
                // 该路径为上传文件的临时路径
                $path = $file->getPath();

                // 由于 Swoole 上传文件的 tmp_name 并没有保持文件原名，所以这个方法已重写为获取原文件名的后缀名
                $extension = $file->getExtension();

                $size = $file->getSize();
                //file_put_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . "./size.txt", date("Y-m-d H:i:s") . "  " . var_export($size,true) . "\r\n", FILE_APPEND);
                $zkb = formatBytes($size);
                if((float)$zkb >= 2048) return fail('该组文件大小不得超过2M');

                //判断目录是否存在
                $lujing = BASE_PATH . '/public/static';
                if(!is_dir($lujing)){
                    mkdir(iconv("UTF-8", "GBK", $lujing),0777,true);
                }

                // 上传文件
                $filename = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $extension;

                $file->moveTo(BASE_PATH . '/public/static/' . $filename);

                // 通过 isMoved(): bool 方法判断方法是否已移动
                if ($file->isMoved()) {
                    $new_file =
                    $url[$i] = str_replace($this->request->getRequestTarget(),'',$this->request->getUri()) . '/static/'.$filename;
                }
                $i++;
            }
        }
        return success(['path' => $url]);
    }

    /**
     * @param \Hyperf\Filesystem\FilesystemFactory $factory
     * @return array
     * @throws \League\Flysystem\FileExistsException
     * 其它云存储上传方法
     */
    public function ossUpload(\Hyperf\Filesystem\FilesystemFactory $factory)
    {
        $file = $this->request->file('file');
        if ($file->isValid()) {
            // 该路径为上传文件的临时路径
            $path = $file->getPath();
            // 由于 Swoole 上传文件的 tmp_name 并没有保持文件原名，所以这个方法已重写为获取原文件名的后缀名
            $extension = $file->getExtension();
            $size = $file->getSize();
            //file_put_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . "./size.txt", date("Y-m-d H:i:s") . "  " . var_export($size,true) . "\r\n", FILE_APPEND);
            $zkb = formatBytes($size);
            if((float)$zkb >= 2048) return fail('文件大小不得超过2M');

            //打开临时文件
            $stream = fopen($file->getRealPath(), 'r+');
            //文件地址
            $filename = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $extension;

            #上传本地
//            $local = $factory->get('local');var_dump($local->getConfig()->get('root'));
//            $local->write($filename, $stream);
//            fclose($stream);
//            $domain = str_replace($this->request->getRequestTarget(),'',$this->request->getUri()) . '/static/'.$filename;

            #阿里云
//            $oss = $factory->get('oss');
//            $oss->writeStream($filename, $stream);
//            fclose($stream);
//            $domain = 'http://'.$oss->getConfig()->get('endpoint').'/'.$filename;

            #七牛云
            $qiniu = $factory->get('qiniu');
            $qiniu->writeStream($filename, $stream);
            fclose($stream);
            $domain = 'https://'.$qiniu->getConfig()->get('domain').'/'.$filename;

            return success(['path' => $domain]);
        }else{
            return fail('缺少参数');
        }
    }
}

