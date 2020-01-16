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
use Illuminate\Support\Facades\Log;

class EntityFieldController extends Controller
{
    protected $formNames = [
        'name', 'type', 'comment', 'form_name', 'form_type', 'is_show', 'is_edit', 'is_required',
        'form_comment', 'entity_id', 'field_length', 'field_total', 'field_scale', 'order', 'form_params',
        'default_value', 'is_show_inline', 'form_default_value'
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
        EntityField::$searchField['entity_id']['enums'] = EntityRepository::all()->pluck('name', 'id');
        return view('admin.entityField.index', ['breadcrumb' => $this->breadcrumb]);
    }

    /**
     * 模型字段管理-模型字段列表数据接口
     *
     * @param Request $request
     * @return array
     */
    public function list(Request $request)
    {
        $perPage = (int) $request->get('limit', 50);
        $this->formNames[] = 'created_at';
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
            $data['is_show'] = $data['is_show'] ?? EntityField::SHOW_DISABLE;
            $data['is_edit'] = $data['is_edit'] ?? EntityField::EDIT_DISABLE;
            $data['is_required'] = $data['is_required'] ?? EntityField::REQUIRED_DISABLE;
            $data['is_show_inline'] = $data['is_show_inline'] ?? EntityField::SHOW_NOT_INLINE;
            $modifyDB = $request->post('is_modify_db');

            $table = EntityRepository::find($data['entity_id']);
            if (!$table) {
                return [
                    'code' => 1,
                    'msg' => '新增失败：模型不存在',
                ];
            }
            if ($modifyDB && Schema::hasColumn($table->table_name, $data['name'])) {
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
            // 一个模型只能有一个 inputTags 表单类型
            if (EntityFieldRepository::formTypeBeUnique($data['form_type'])
                && EntityFieldRepository::getInputTagsField($data['entity_id'])) {
                return [
                    'code' => 4,
                    'msg' => '新增失败：一个模型只能有一个标签输入框表单类型',
                ];
            }

            // inputTags类型表单不需要添加数据库字段
            if (in_array($data['form_type'], ['inputTags'], true)) {
                $modifyDB = false;
            }
            if ($modifyDB) {
                Schema::table($table->table_name, function (Blueprint $table) use ($data) {
                    $m = $data['type'];
                    $length = intval($data['field_length']);
                    $total = intval($data['field_total']);
                    $scale = intval($data['field_scale']);
                    if (in_array($m, ['char', 'string'])) {
                        $table->$m($data['name'], $length > 0 ? $length : 255)
                            ->comment($data['comment'])
                            ->default(strval($data['default_value']));
                    } elseif (Str::contains(Str::lower($m), 'integer')) {
                        $table->$m($data['name'])
                            ->comment($data['comment'])
                            ->default(intval($data['default_value']));
                    } elseif (in_array($m, ['float', 'double', 'decimal', 'unsignedDecimal'])) {
                        if ($total > 0 && $scale > 0 && $total > $scale) {
                            $table->$m($data['name'], $total, $scale)
                                ->comment($data['comment'])
                                ->default(doubleval($data['default_value']));
                        } else {
                            $table->$m($data['name'])
                                ->comment($data['comment'])
                                ->default(doubleval($data['default_value']));
                        }
                    } else {
                        $table->$m($data['name'])->comment($data['comment'])->nullable();
                    }
                });
            }

            unset($data['field_length'], $data['field_total'], $data['field_scale']);
            EntityFieldRepository::add($data);

            return [
                'code' => 0,
                'msg' => '新增成功',
                'redirect' => true
            ];
        } catch (\Exception $e) {
            Log::error($e);
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
        $data['is_show'] = $data['is_show'] ?? EntityField::SHOW_DISABLE;
        $data['is_edit'] = $data['is_edit'] ?? EntityField::EDIT_DISABLE;
        $data['is_required'] = $data['is_required'] ?? EntityField::REQUIRED_DISABLE;
        $data['is_show_inline'] = $data['is_show_inline'] ?? EntityField::SHOW_NOT_INLINE;
        // 一个模型只能有一个 inputTags 表单类型
        if (EntityFieldRepository::formTypeBeUnique($data['form_type']) && EntityFieldRepository::getInputTagsField($data['entity_id'])) {
            return [
                'code' => 4,
                'msg' => '编辑失败：一个模型只能有一个标签输入框表单类型',
            ];
        }
        try {
            unset($data['field_length'], $data['field_total'], $data['field_scale'], $data['entity_id']);
            EntityFieldRepository::update($id, $data);
            return [
                'code' => 0,
                'msg' => '编辑成功',
                'redirect' => true
            ];
        } catch (QueryException $e) {
            Log::error($e);
            return [
                'code' => 1,
                'msg' => '编辑失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前模型字段已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }

    /**
     * 模型字段管理-删除模型字段
     *
     * @param int $id
     * @return array
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
                'redirect' => true
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

    /**
     * 模型字段管理-字段快捷更新接口
     *
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function listUpdate(Request $request, $id)
    {
        try {
            $entityField = EntityField::query()->findOrFail($id);

            $data = $request->only(['is_show', 'is_edit', 'is_required', 'order', 'is_show_inline']);
            foreach ($data as $key => $value) {
                $entityField->$key = $value;
            }
            $entityField->save();
            return [
                'code' => 0,
                'msg' => '保存成功',
                'redirect' => true
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'code' => 2,
                'msg' => '保存失败：记录不存在',
                'redirect' => false
            ];
        }
    }
}
