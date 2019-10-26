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

class UEditorController extends NEditorController
{
    /**
     * ueditor-ueditor后端服务
     *
     * @param Request $request
     * @return array|mixed
     */
    public function serve(Request $request, $type = null)
    {
        $action = $request->input('action');
        if (!method_exists(self::class, $action)) {
            return [
                'state' => '未知操作',
            ];
        }

        return call_user_func(self::class . '::' . $action, $request);
    }

    protected function config()
    {
        return config('ueditor');
    }
}
