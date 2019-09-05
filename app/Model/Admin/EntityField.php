<?php
/**
 * @author  Eddy <cumtsjh@163.com>
 */

namespace App\Model\Admin;

class EntityField extends Model
{
    const SHOW_ENABLE = 1;
    const SHOW_DISABLE = 0;

    const SHOW_INLINE = 1;
    const SHOW_NOT_INLINE = 0;

    const EDIT_ENABLE = 1;
    const EDIT_DISABLE = 0;

    const REQUIRED_ENABLE = 1;
    const REQUIRED_DISABLE = 0;

    protected $guarded = [];

    public function entity()
    {
        return $this->belongsTo('App\Model\Admin\Entity', 'entity_id');
    }

    public static $listField = [
        'entityName' => '模型',
        'name' => '字段名称',
        'type' => '字段类型',
        'form_name' => '表单名称',
        'form_type' => ['title' => '表单类型', 'sort' => true],
        'is_show_inline' => [
            'title' => '行内展示', 'sort' => true, 'templet' => '#isShowInlineTemplet', 'event' => 'showInlineEvent'
        ],
        'is_show' => ['title' => '显示', 'templet' => '#isShowTemplet', 'event' => 'showEvent'],
        'order' => ['title' => '排序', 'sort' => true, 'edit' => true, 'width' => 80],
    ];

    public static $searchField = [
        'name' => '字段名称',
        'entity_id' => [
            'title' => '模型',
            'searchType' => '=',
            'showType' => 'select',
            'enums' => [],
        ]
    ];
}
