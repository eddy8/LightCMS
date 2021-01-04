<?php
/**
 * Date: 2019/3/16 Time: 13:42
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class NEditorController extends Controller
{
    /**
     * 基础功能-图片上传
     *
     * @param Request $request
     * @param string $type
     * @return array
     */
    public function serve(Request $request, $type)
    {
        if (!method_exists(self::class, $type)) {
            return [
                'code' => 1,
                'msg' => '未知操作'
            ];
        }

        return call_user_func(self::class . '::' . $type, $request);
    }

    protected function uploadImage(Request $request)
    {
        if (config('light.image_upload.driver') !== 'local') {
            $class = config('light.image_upload.class');
            return call_user_func([new $class, 'uploadImage'], $request);
        }

        if (!$request->hasFile('file')) {
            return [
                'code' => 2,
                'msg' => '非法请求'
            ];
        }
        $file = $request->file('file');
        if (!$this->isValidImage($file)) {
            return [
                'code' => 3,
                'msg' => '文件不合要求'
            ];
        }

        $result = $file->store(date('Ym'), config('light.neditor.disk'));
        if (!$result) {
            return [
                'code' => 3,
                'msg' => '上传失败'
            ];
        }

        return [
            'code' => 200,
            'state' => 'SUCCESS', // 兼容ueditor
            'msg' => '',
            'url' => Storage::disk(config('light.neditor.disk'))->url($result),
        ];
    }

    public function catchImage(Request $request)
    {
        if (config('light.image_upload.driver') !== 'local') {
            $class = config('light.image_upload.class');
            return call_user_func([new $class, 'catchImage'], $request);
        }

        $files = (array) $request->post('file');
        $urls = [];
        foreach ($files as $v) {
            $extention = pathinfo(parse_url($v, PHP_URL_PATH), PATHINFO_EXTENSION);
            $path = date('Ym') . '/' . md5($v) . '.' . ($extention == '' ? 'jpg' : $extention);
            Storage::disk(config('light.neditor.disk'))
                ->put($path, file_get_contents($v));
            $urls[] = [
                'url' => Storage::disk(config('light.neditor.disk'))->url($path),
                'source' => $v,
                'state' => 'SUCCESS'
            ];
        }

        return [
           'list' => $urls
        ];
    }

    public function uploadVideo(Request $request)
    {
        if (config('light.image_upload.driver') !== 'local') {
            $class = config('light.image_upload.class');
            return call_user_func([new $class, 'uploadImage'], $request);
        }

        if (!$request->hasFile('file')) {
            return [
                'code' => 2,
                'msg' => '非法请求'
            ];
        }
        $file = $request->file('file');
        if (!$this->isValidVideo($file)) {
            return [
                'code' => 3,
                'msg' => '文件不合要求'
            ];
        }

        $result = $file->store('video/' . date('Ym'), config('light.neditor.disk'));
        if (!$result) {
            return [
                'code' => 3,
                'msg' => '上传失败'
            ];
        }

        return [
            'code' => 200,
            'state' => 'SUCCESS', // 兼容ueditor
            'msg' => '',
            'url' => Storage::disk(config('light.neditor.disk'))->url($result),
        ];
    }

    public function uploadFile(Request $request)
    {
        if (config('light.image_upload.driver') !== 'local') {
            $class = config('light.image_upload.class');
            return call_user_func([new $class, 'uploadImage'], $request);
        }

        if (!$request->hasFile('file')) {
            return [
                'code' => 2,
                'msg' => '非法请求'
            ];
        }
        $file = $request->file('file');
        if (!$this->isValidFile($file)) {
            return [
                'code' => 3,
                'msg' => '文件不合要求'
            ];
        }

        $result = $file->store('file/' . date('Ym'), config('light.neditor.disk'));
        if (!$result) {
            return [
                'code' => 3,
                'msg' => '上传失败'
            ];
        }

        return [
            'code' => 200,
            'state' => 'SUCCESS', // 兼容ueditor
            'msg' => '',
            'url' => Storage::disk(config('light.neditor.disk'))->url($result),
        ];
    }

    protected function isValidImage(UploadedFile $file)
    {
        $c = config('light.neditor.upload');
        $config = [
            'maxSize' => $c['imageMaxSize'],
            'AllowFiles' => $c['imageAllowFiles'],
        ];

        return $this->isValidUploadedFile($file, $config);
    }

    protected function isValidVideo(UploadedFile $file)
    {
        $c = config('light.neditor.upload');
        $config = [
            'maxSize' => $c['videoMaxSize'],
            'AllowFiles' => $c['videoAllowFiles'],
        ];

        return $this->isValidUploadedFile($file, $config);
    }

    protected function isValidFile(UploadedFile $file)
    {
        $c = config('light.neditor.upload');
        $config = [
            'maxSize' => $c['fileMaxSize'],
            'AllowFiles' => $c['fileAllowFiles'],
        ];

        return $this->isValidUploadedFile($file, $config);
    }

    protected function isValidUploadedFile(UploadedFile $file, array $config)
    {
        if (!$file->isValid() ||
            $file->getSize() > $config['maxSize'] ||
            !in_array(
                '.' . strtolower($file->getClientOriginalExtension()),
                $config['AllowFiles']
            ) ||
            !in_array(
                '.' . strtolower($file->guessExtension()),
                $config['AllowFiles']
            )
        ) {
            return false;
        }

        return true;
    }
}
