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
    ]
];
