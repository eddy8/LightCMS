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
use Intervention\Image\Facades\Image;
use Intervention\Image\Exception\NotReadableException;

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

        $files = array_unique((array) $request->post('file'));
        $urls = [];
        foreach ($files as $v) {
            $image = $this->fetchImageFile($v);
            if (!$image || !$image['extension'] || !$this->isAllowedImageType($image['extension'])) {
                continue;
            }

            $path = date('Ym') . '/' . md5($v) . '.' . $image['extension'];
            Storage::disk(config('light.neditor.disk'))
                ->put($path, $image['data']);
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

    protected function isValidImage(UploadedFile $file)
    {
        if (!$file->isValid() ||
            $file->getSize() > config('light.neditor.upload.imageMaxSize') ||
            !in_array(
                '.' . strtolower($file->getClientOriginalExtension()),
                config('light.neditor.upload.imageAllowFiles')
            ) ||
            !in_array(
                '.' . strtolower($file->guessExtension()),
                config('light.neditor.upload.imageAllowFiles')
            )
        ) {
            return false;
        }

        return true;
    }

    protected function fetchImageFile($url)
    {
        try {
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return false;
            }

            $ch = curl_init();
            $options =  [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.2 (KHTML, like Gecko) Chrome/22.0.1216.0 Safari/537.2'
            ];
            curl_setopt_array($ch, $options);
            $data = curl_exec($ch);
            curl_close($ch);
            if (!$data) {
                return false;
            }

            if (isWebp($data)) {
                $image = Image::make(imagecreatefromwebp($url));
                $extension = 'webp';
            } else {
                $resource = @imagecreatefromstring($data);

                if ($resource === false) {
                    throw new NotReadableException(
                        "Unable to init from given binary data."
                    );
                }
                $image = Image::make($resource);
                $image->mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $data);
            }
        } catch (NotReadableException $e) {
            return false;
        }

        $mime = $image->mime();
        return [
            'extension' => $extension ?? ($mime ? strtolower(explode('/', $mime)[1]) : ''),
            'data' => $data
        ];
    }

    protected function isAllowedImageType($extension)
    {
        $c = config('light.neditor.upload');

        return in_array('.' . $extension, $c['imageAllowFiles'], true);
    }
}
