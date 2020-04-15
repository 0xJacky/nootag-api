<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: UploadHandler.php
 * Description: 图片上传处理
 * Date: 2020/01/01
 * Time: 9:24 下午
 */

namespace App\Handlers;

use OSS\OssClient;
use OSS\Core\OssException;
use App\Models\Upload;

class UploadHandler
{
    /**
     * @var OssClient
     */
    private $ossClient;

    private static $bucket = '';
    private static $endpoint = '';
    private static $domain = '';

    public function __construct()
    {
        self::$bucket = config('aliyun.oss_bucket');
        self::$endpoint = config('aliyun.endpoint');
        self::$domain = config('aliyun.oss_domain');

        $this->ossClient = new OssClient(config('aliyun.access_key_id'),
            config('aliyun.access_key_secret'), self::$endpoint);
    }

    public function store($file, $style, $user_id, $file_name = null)
    {
        $ext = $file->getClientOriginalExtension();
        if ($ext === 'mobileconfig') {
            $name = $file->getClientOriginalName();
        } else {
            $name = uniqid('', TRUE) . '.' . $ext;
        }

        $oss_path = date('Y/m') . '/' . $name;

        /* 拼接访问地址 */
        if ($style) {
            $url = self::$domain . $oss_path . '!' . $style;
        } else {
            $url = self::$domain . $oss_path;
        }

        $id = $this->log($name, $oss_path, $style, $file->getSize(), $file->getMimeType(), $url, $user_id);

        try {
            $this->ossClient->multiuploadFile(self::$bucket, $oss_path, $file->getRealPath());
            return ['success' => true, 'url' => $url, 'id' => $id, 'name' => $name];
        } catch (OssException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private static function log($name, $oss_path, $style, $size, $mime_type, $url, $user_id)
    {
        $log = Upload::create([
            'name' => $name,
            'oss_path' => $oss_path,
            'style' => $style,
            'size' => $size,
            'mime_type' => $mime_type,
            'url' => $url,
            'user_id' => $user_id
        ]);
        return $log->id;
    }

    public function delete($log_id)
    {
        if (!$log_id) {
            return;
        }
        $file = Upload::find($log_id);
        self::delete_log($log_id);
        if (empty($file->oss_path)) {
            return;
        }
        return $this->ossClient->deleteObject(self::$bucket, $file->oss_path);
    }

    private static function delete_log($log_id)
    {
        return Upload::destroy($log_id);
    }

}
