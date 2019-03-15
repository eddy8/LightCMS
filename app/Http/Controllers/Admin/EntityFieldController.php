<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EntityFieldRequest;
use App\Model\Admin\Entity;
use App\Model\Admin\EntityField;
use App\Repository\Admin\EntityFieldRepository;
use App\Repository\Admin\EntityRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Schema;
use Log;

class EntityFieldController extends Controller
{
    protected $formNames = [
        'name', 'type', 'comment', 'form_name', 'form_type', 'is_show', 'is_edit', 'is_required',
        'form_comment', 'entity_id', 'field_length', 'field_total', 'field_scale',
    ];

    public function __construct()
    {
        parent::__construct();

        $this->breadcrumb[] = ['title' => '模型字段列表', 'url' => route('admin::entityField.index')];
    }

    /**
     * 模型字段管理-模型字段列表
     *
     */
    public function index()
    {
        $this->breadcrumb[] = ['title' => '模型字段列表', 'url' => ''];
        return view('admin.entityField.index', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 模型字段列表数据接口
     *
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        $perPage = (int) $request->get('limit', 50);
        $condition = $request->only($this->formNames);

        $data = EntityFieldRepository::list($perPage, $condition);

        return $data;
    }

    /**
     * 模型字段管理-新增模型字段
     *
     */
    public function create()
    {
        $entity = Entity::query()->pluck('name', 'id')->all();
        $this->breadcrumb[] = ['title' => '新增模型字段', 'url' => ''];
        return view('admin.entityField.add', ['breadcrumb' => $this->breadcrumb, 'entity' => $entity]);
    }

    /**
     * 模型字段管理-保存模型字段
     *
     * @param EntityFieldRequest $request
     * @return array
     */
    public function save(EntityFieldRequest $request)
    {
        try {
            $data = $request->only($this->formNames);
            $table = EntityRepository::find($data['entity_id']);
            if (!$table) {
                return [
                    'code' => 1,
                    'msg' => '新增失败：模型不存在',
                ];
            }
            if (Schema::hasColumn($table->table_name, $data['name'])) {
                return [
                    'code' => 2,
                    'msg' => '新增失败：字段已存在',
                ];
            }
            if (!in_array($data['type'], config('light.db_table_field_type'))) {
                return [
                    'code' => 3,
                    'msg' => '新增失败：无效字段类型',
                ];
            }

            Schema::table($table->table_name, function (Blueprint $table) use ($data) {
                $m = $data['type'];
                $length = intval($data['field_length']);
                $total = intval($data['field_total']);
                $scale = intval($data['field_scale']);
                if (in_array($m, ['char', 'string']) && $length > 0) {
                    $table->$m($data['name'], $length)->comment($data['comment']);
                } elseif (in_array($m, ['float', 'double', 'decimal', 'unsignedDecimal'])) {
                    if ($total > 0 && $scale > 0 && $total > $scale) {
                        $table->$m($data['name'], $total, $scale)->comment($data['comment']);
                    }
                } else {
                    $table->$m($data['name'])->comment($data['comment']);
                }
            });

            unset($data['field_length'], $data['field_total'], $data['field_scale']);
            EntityFieldRepository::add($data);

            return [
                'code' => 0,
                'msg' => '新增成功',
                'redirect' => route('admin::entityField.index')
            ];
        } catch (\Throwable $e) {
            \Log::error($e);
            return [
                'code' => 1,
                'msg' => '新增失败：' . $e->getMessage(),
            ];
        }
    }

    /**
     * 模型字段管理-编辑模型字段
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $this->breadcrumb[] = ['title' => '编辑模型字段', 'url' => ''];

        $model = EntityFieldRepository::find($id);
        $entity = Entity::query()->pluck('name', 'id')->all();
        return view('admin.entityField.add', [
            'id' => $id,
            'model' => $model,
            'breadcrumb' => $this->breadcrumb,
            'entity' => $entity
        ]);
    }

    /**
     * 模型字段管理-更新模型字段
     *
     * @param EntityFieldRequest $request
     * @param int $id
     * @return array
     */
    public function update(EntityFieldRequest $request, $id)
    {
        $data = $request->only($this->formNames);
        try {
            if (!isset($data['is_show'])) {
                $data['is_show'] = EntityField::SHOW_DISABLE;
            }
            if (!isset($data['is_edit'])) {
                $data['is_edit'] = EntityField::EDIT_DISABLE;
            }
            if (!isset($data['is_required'])) {
                $data['is_required'] = EntityField::REQUIRED_DISABLE;
            }

            unset($data['field_length'], $data['field_total'], $data['field_scale'], $data['entity_id']);
            EntityFieldRepository::update($id, $data);
            return [
                'code' => 0,
                'msg' => '编辑成功',
                'redirect' => route('admin::entityField.index')
            ];
        } catch (QueryException $e) {
            \Log::error($e);
            return [
                'code' => 1,
                'msg' => '编辑失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前模型字段已存在' : '其它错误'),
                'redirect' => route('admin::entityField.index')
            ];
        }
    }

    /**
     * 模型字段管理-删除模型字段
     *
     * @param int $id
     */
    public function delete($id)
    {
        try {
            $entityField = EntityField::query()->findOrFail($id);
            $entity = $entityField->entity;
            Schema::table($entity->table_name, function (Blueprint $table) use ($entityField) {
                $table->dropColumn($entityField->name);
            });
            EntityFieldRepository::delete($id);
            return [
                'code' => 0,
                'msg' => '删除成功',
                'redirect' => route('admin::menu.index')
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'code' => 2,
                'msg' => '删除失败：字段不存在',
                'redirect' => false
            ];
        } catch (\RuntimeException $e) {
            return [
                'code' => 1,
                'msg' => '删除失败：' . $e->getMessage(),
                'redirect' => false
            ];
        }
    }

}
