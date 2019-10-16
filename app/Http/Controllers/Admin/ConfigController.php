<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ConfigRequest;
use App\Repository\Admin\ConfigRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ConfigController extends Controller
{
    protected $formNames = ['name', 'key', 'value', 'type', 'group', 'remark'];

    public function __construct()
    {
        parent::__construct();

        $this->breadcrumb[] = ['title' => '配置列表', 'url' => route('admin::config.index')];
    }

    /**
     * 系统管理
     *
     */
    public function index()
    {
        $this->breadcrumb[] = ['title' => '配置列表', 'url' => ''];
        return view(
            'admin.config.index',
            ['breadcrumb' => $this->breadcrumb, 'groups' => ConfigRepository::groupNames()]
        );
    }

    /**
     * 配置管理-配置列表数据接口
     *
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        $perPage = (int) $request->get('limit', 50);
        $this->formNames[] = 'created_at';
        $condition = $request->only($this->formNames);
        if (isset($condition['group'])) {
            $condition['group'] = ['=', $condition['group']];
        }

        $data = ConfigRepository::list($perPage, $condition);

        return $data;
    }

    /**
     * 配置管理-新增配置
     *
     */
    public function create()
    {
        $this->breadcrumb[] = ['title' => '新增配置', 'url' => ''];
        return view('admin.config.add', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 配置管理-保存配置
     *
     * @param ConfigRequest $request
     * @return array
     */
    public function save(ConfigRequest $request)
    {
        try {
            ConfigRepository::add($request->only($this->formNames));
            Cache::forget(config('light.cache_key.config'));
            return [
                'code' => 0,
                'msg' => '新增成功',
                'redirect' => true
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => '新增失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前配置已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }

    /**
     * 配置管理-编辑配置
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $this->breadcrumb[] = ['title' => '编辑配置', 'url' => ''];

        $model = ConfigRepository::find($id);
        return view('admin.config.add', ['id' => $id, 'model' => $model, 'breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 配置管理-更新配置
     *
     * @param ConfigRequest $request
     * @param int $id
     * @return array
     */
    public function update(ConfigRequest $request, $id)
    {
        $data = $request->only($this->formNames);
        try {
            ConfigRepository::update($id, $data);
            Cache::forget(config('light.cache_key.config'));
            return [
                'code' => 0,
                'msg' => '编辑成功',
                'redirect' => true
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => '编辑失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前配置已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }
}
