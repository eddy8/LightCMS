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

    // 系统日志保留时间。单位：天
    'log_reserve_days' => 180,

    // 异步写入系统日志
    'log_async_write' => false,

    // 数据库表字段类型 参考：https://laravel.com/docs/5.5/migrations#columns
    'db_table_field_type' => [
        'string',
        'char',
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
        'inputAutoComplete' => '短文本（input，自动完成）',
        'textArea' => '长文本（textarea）',
        'richText' => '富文本',
        'markdown' => '富文本（markdown）',
        'password' => '密码字符',
        'option' => '单选框',
        'checkbox' => '复选框',
        'select' => '下拉选择',
        'selectSingleSearch' => '下拉选择（远程搜索）',
        'selectMulti' => '下拉选择（多选）',
        'selectMultiSearch' => '下拉选择（多选，远程搜索）',
        'inputTags' => '标签输入框',
        'upload' => '图片上传（单图）',
        'uploadMulti' => '图片上传（多图）',
        'datetime' => '日期时间',
        'date' => '日期',
        'reference_category' => '引用分类数据',
        'reference_admin_user' => '引用管理员数据'
    ],

    // NEditor相关
    'neditor' => [
        'disk' => 'admin_img',
        'upload' => [
            'imageMaxSize' => 8 * 1024 * 1024, /* 上传大小限制，单位B */
            'imageAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp', ".webp"], /* 上传图片格式显示 */
            "videoMaxSize" => 100 * 1024 * 1024, /* 上传大小限制，单位B */
            "videoAllowFiles" => [
                ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
                ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"
            ], /* 上传视频格式显示 */
            "fileMaxSize" => 50 * 1024 * 1024, /* 上传大小限制，单位B */
            "fileAllowFiles" => [
                ".png", ".jpg", ".jpeg", ".gif", ".bmp", ".webp",
                ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
                ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
                ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
                ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
            ], /* 上传文件格式显示 */
        ]
    ],
    'image_upload' => [
        'driver' => 'local', // local 表示上传到本地服务器。上传到其它服务器请设置自定义名称
        'class' => '', // 自定义 driver 需要填写对应包括命名空间的完整类名，该类需要实现 App\Contracts\ImageUpload 接口
    ],

    // 三方登录
    'auth_login' => [
        'weibo' => [
            'client_id' => env('WEIBO_CLIENT_ID', ''),
            'client_secret' => env('WEIBO_CLIENT_SECRET', ''),
            'redirect' => env('WEIBO_REDIRECT', ''),
        ],
        'qq' => [
            'client_id' => env('QQ_CLIENT_ID', ''),
            'client_secret' => env('QQ_CLIENT_SECRET', ''),
            'redirect' => env('QQ_REDIRECT', ''),
        ],
        'wechat' => [
            'client_id' => env('WECHAT_CLIENT_ID', ''),
            'client_secret' => env('WECHAT_CLIENT_SECRET', ''),
            'redirect' => env('WECHAT_REDIRECT', ''),
        ],
    ]
];
