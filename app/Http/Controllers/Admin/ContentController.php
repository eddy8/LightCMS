<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Http\Controllers\Admin;

use App\Events\ContentCreated;
use App\Events\ContentCreating;
use App\Events\ContentDeleted;
use App\Events\ContentUpdated;
use App\Events\ContentUpdating;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContentRequest;
use App\Model\Admin\Content;
use App\Model\Admin\Entity;
use App\Model\Admin\EntityField;
use App\Repository\Admin\ContentRepository;
use App\Repository\Admin\EntityFieldRepository;
use App\Repository\Admin\EntityRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Model\Admin\Tag;
use App\Model\Admin\ContentTag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContentController extends Controller
{
    protected $formNames = [];

    protected $entity = null;

    public function __construct()
    {
        parent::__construct();
        $route = request()->route();
        if (is_null($route)) {
            return;
        }
        $entity = $route->parameter('entity');
        $this->entity = Entity::query()->findOrFail($entity);
        ContentRepository::setTable($this->entity->table_name);
        $this->breadcrumb[] = ['title' => '内容列表', 'url' => route('admin::content.index', ['entity' => $entity])];
    }

    /**
     * 内容管理-内容列表
     *
     */
    public function index($entity)
    {
        $result = $this->useUserDefinedIndexHandler($entity);
        if (!is_null($result)) {
            return $result;
        }

        $this->breadcrumb[] = ['title' => $this->entity->name . '内容列表', 'url' => ''];
        Content::$listField = [
            'title' => '标题'
        ];
        return view('admin.content.index', [
            'breadcrumb' => $this->breadcrumb,
            'entity' => $entity,
            'entityModel' => $this->entity,
            'autoMenu' => EntityRepository::systemMenu()
        ]);
    }

    /**
     * 内容管理-内容列表数据接口
     *
     * @param Request $request
     * @param integer $entity
     * @return array
     */
    public function list(Request $request, $entity)
    {
        $result = $this->useUserDefinedListHandler($request, $entity);
        if (!is_null($result)) {
            return $result;
        }

        $perPage = (int) $request->get('limit', 50);
        $this->formNames = array_merge(['created_at', 'light_sort_fields'], EntityFieldRepository::getFields($entity));
        $condition = $request->only($this->formNames);

        $data = ContentRepository::list($entity, $perPage, $condition);

        return $data;
    }

    /**
     * 内容管理-新增内容
     *
     */
    public function create($entity)
    {
        $this->breadcrumb[] = ['title' => "新增{$this->entity->name}内容", 'url' => ''];
        $view = $this->getAddOrEditViewPath();

        return view($view, [
            'breadcrumb' => $this->breadcrumb,
            'entity' => $entity,
            'entityModel' => $this->entity,
            'entityFields' => EntityFieldRepository::getByEntityId($entity),
            'autoMenu' => EntityRepository::systemMenu()
        ]);
    }

    /**
     * 内容管理-保存内容
     *
     * @param ContentRequest $request
     * @param integer $entity
     * @return array
     */
    public function save(ContentRequest $request, $entity)
    {
        $this->validateEntityRequest();
        event(new ContentCreating($request, $this->entity));
        $result = $this->useUserDefinedSaveHandler($request, $entity);
        if (!is_null($result)) {
            return $result;
        }

        try {
            DB::beginTransaction();

            $content = ContentRepository::add($request->only(
                EntityFieldRepository::getSaveFields($entity)
            ), $this->entity);

            // 标签类型字段另外处理 多对多关联
            $inputTagsField = EntityFieldRepository::getInputTagsField($entity);
            $tags = null;
            if ($inputTagsField) {
                $tags = $request->post($inputTagsField->name);
            }
            if (is_string($tags) && $tags = json_decode($tags, true)) {
                foreach ($tags as $v) {
                    $tag = Tag::firstOrCreate(['name' => $v['value']]);
                    ContentTag::firstOrCreate(
                        ['entity_id' => $entity, 'content_id' => $content->id, 'tag_id' => $tag->id]
                    );
                }
            }

            DB::commit();
            event(new ContentCreated($content, $this->entity));

            return [
                'code' => 0,
                'msg' => '新增成功',
                'redirect' => route('admin::content.index', ['entity' => $entity])
            ];
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error($e);
            return [
                'code' => 1,
                'msg' => '新增失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前内容已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }

    /**
     * 内容管理-编辑内容
     *
     * @param int $id
     * @return View
     */
    public function edit($entity, $id)
    {
        $this->breadcrumb[] = ['title' => "编辑{$this->entity->name}内容", 'url' => ''];
        $view = $this->getAddOrEditViewPath();
        $model = ContentRepository::find($id);

        return view($view, [
            'id' => $id,
            'model' => $model,
            'breadcrumb' => $this->breadcrumb,
            'entity' => $entity,
            'entityModel' => $this->entity,
            'entityFields' => EntityFieldRepository::getByEntityId($entity),
            'autoMenu' => EntityRepository::systemMenu()
        ]);
    }

    /**
     * 内容管理-更新内容
     *
     * @param ContentRequest $request
     * @param integer $entity
     * @param int $id
     * @return array
     */
    public function update(ContentRequest $request, $entity, $id)
    {
        $this->validateEntityRequest();
        event(new ContentUpdating($request, $this->entity));
        $result = $this->useUserDefinedUpdateHandler($request, $entity, $id);
        if (!is_null($result)) {
            return $result;
        }

        $data = $this->getUpdateData($request, $entity);
        try {
            DB::beginTransaction();

            ContentRepository::update($id, $data, $this->entity);
            // 标签类型字段另外处理 多对多关联
            $inputTagsField = EntityFieldRepository::getInputTagsField($entity);
            $tags = null;
            if ($inputTagsField && intval($inputTagsField->is_edit) === EntityField::EDIT_ENABLE) {
                $tags = $request->post($inputTagsField->name);
            }
            if (is_string($tags) && $tags = json_decode($tags, true)) {
                $tagIds = [];
                foreach ($tags as $v) {
                    $tag = Tag::firstOrCreate(['name' => $v['value']]);
                    ContentTag::firstOrCreate(['entity_id' => $entity, 'content_id' => $id, 'tag_id' => $tag->id]);
                    $tagIds[] = $tag->id;
                }
                if ($tagIds) {
                    ContentTag::where('entity_id', $entity)->where('content_id', $id)->whereNotIn('tag_id', $tagIds)->delete();
                }
            }

            DB::commit();
            event(new ContentUpdated([$id], $this->entity));

            return [
                'code' => 0,
                'msg' => '编辑成功',
                'redirect' => route('admin::content.index', ['entity' => $entity])
            ];
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error($e);
            return [
                'code' => 1,
                'msg' => '编辑失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前内容已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }

    /**
     * 内容管理-删除内容
     *
     * @param int $id
     */
    public function delete($entity, $id)
    {
        try {
            $content = ContentRepository::findOrFail($id);
            ContentRepository::delete($id);
            event(new ContentDeleted(collect([$content]), $this->entity));

            return [
                'code' => 0,
                'msg' => '删除成功',
                'reload' => true
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
     * 内容管理-内容批量操作
     *
     * @param Request $request
     * @return array
     */
    public function batch(Request $request)
    {
        $type = $request->input('type', '');
        $ids = $request->input('ids');
        if (!is_array($ids)) {
            return [
                'code' => 1,
                'msg' => '参数错误'
            ];
        }
        $ids = array_map(function ($item) {
            return intval($item);
        }, $ids);

        $message = '';
        switch ($type) {
            case 'delete':
                $contents = ContentRepository::model()->whereIn('id', $ids)->get();
                ContentRepository::model()->whereIn('id', $ids)->delete();
                event(new ContentDeleted($contents, $this->entity));
                break;
            default:
                break;
        }

        return [
            'code' => 0,
            'msg' => '操作成功' . $message,
            'reload' => true
        ];
    }

    protected function validateEntityRequest()
    {
        $entityRequestClass = '\\App\\Http\\Requests\\Admin\\Entity\\' .
            Str::ucfirst(Str::singular($this->entity->table_name)) . 'Request';
        if (class_exists($entityRequestClass)) {
            $entityRequestClass::capture()->setContainer(app())->setRedirector(app()->make('redirect'))->validateResolved();
        }
    }

    protected function useUserDefinedSaveHandler($request, $entity)
    {
        $entityControllerClass = $this->userDefinedHandlerExists('save');
        if ($entityControllerClass === false) {
            return null;
        }
        return call_user_func([new $entityControllerClass, 'save'], $request, $entity);
    }

    protected function useUserDefinedUpdateHandler($request, $entity, $id)
    {
        $entityControllerClass = $this->userDefinedHandlerExists('update');
        if ($entityControllerClass === false) {
            return null;
        }
        return call_user_func([new $entityControllerClass, 'update'], $request, $entity, $id);
    }

    protected function useUserDefinedIndexHandler($entity)
    {
        $entityControllerClass = $this->userDefinedHandlerExists('index');
        if ($entityControllerClass === false) {
            return null;
        }
        return call_user_func([new $entityControllerClass, 'index'], $entity);
    }

    protected function useUserDefinedListHandler($request, $entity)
    {
        $entityControllerClass = $this->userDefinedHandlerExists('list');
        if ($entityControllerClass === false) {
            return null;
        }
        return call_user_func([new $entityControllerClass, 'list'], $request, $entity);
    }

    /**
     * 判断自定义的处理方法是否存在
     *
     * @param string $method 方法名
     * @return string|boolean 存在返回控制器类名，不存在返回false
     */
    protected function userDefinedHandlerExists($method)
    {
        $entityControllerClass = '\\App\\Http\\Controllers\\Admin\\Entity\\' .
            Str::ucfirst(Str::camel(Str::singular($this->entity->table_name))) . 'Controller';
        if (class_exists($entityControllerClass) && method_exists($entityControllerClass, $method)) {
            return $entityControllerClass;
        }

        return false;
    }

    protected function getAddOrEditViewPath()
    {
        $view = 'admin.content.add';
        // 自定义模板
        $modelName = Str::singular($this->entity->table_name);
        $path = resource_path('views/admin/content/' . $modelName . '_add.blade.php');
        if (file_exists($path)) {
            $view = 'admin.content.' . $modelName . '_add';
        }

        return $view;
    }

    protected function getUpdateData($request, $entity)
    {
        $fieldInfo = EntityFieldRepository::getUpdateFields($entity);
        $data = [];
        foreach ($fieldInfo as $k => $v) {
            if ($v === 'checkbox') {
                $data[$k] = '';
            }
        }
        return array_merge($data, $request->only(array_keys($fieldInfo)));
    }
}
