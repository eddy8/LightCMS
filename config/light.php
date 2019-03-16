<?php
return [
    // 超级管理员。不受权限控制
    'superAdmin' => [1],

    // 各类缓存KEY
    'cache_key' => [
        'config' => 'config'
    ],
    // 加载数据库自定义配置
    'light_config' => false,

    // 数据库表字段类型 参考：https://laravel.com/docs/5.5/migrations#columns
    'db_table_field_type' => [
        'char',
        'string',
        'text',
        'mediumText',
        'longText',
        'integer',
        'unsignedInteger',
        'tinyInteger',
        'unsignedTinyInteger',
        'smallInteger',
        'unsignedSmallInteger',
        'mediumInteger',
        'unsignedMediumInteger',
        'bigInteger',
        'unsignedBigInteger',
        'float',
        'double',
        'decimal',
        'unsignedDecimal',
        'date',
        'dateTime',
        'dateTimeTz',
        'time',
        'timeTz',
        'timestamp',
        'timestampTz',
        'year',
        'binary',
        'boolean',
        'enum',
        'json',
        'jsonb',
        'geometry',
        'geometryCollection',
        'ipAddress',
        'lineString',
        'macAddress',
        'multiLineString',
        'multiPoint',
        'multiPolygon',
        'point',
        'polygon',
        'uuid',
    ],

    // 表单类型
    'form_type' => [
        'input' => '短文本（input）',
        'textArea' => '长文本（textarea）',
        'richText' => '富文本',
        'password' => '密码字符',
        'option' => '单选框',
        'checkbox' => '复选框',
        'select' => '下拉选择',
        'upload' => '图片上传',
        'reference_category' => '引用分类数据',
        'reference_admin_user' => '引用管理员数据'
    ],

    // NEditor相关
    'neditor' => [
        'disk' => 'admin_img',
        'upload' => [
            'imageMaxSize' => 8 * 1024 * 1024, /* 上传大小限制，单位B */
            'imageAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'], /* 上传图片格式显示 */
        ]
    ]
];
