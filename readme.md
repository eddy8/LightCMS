# LightCMS
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/eddy8/lightCMS/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/eddy8/lightCMS/?branch=master)    [![StyleCI](https://github.styleci.io/repos/175428969/shield?branch=master)](https://github.styleci.io/repos/175428969)    [![Build Status](https://www.travis-ci.org/eddy8/lightCMS.svg?branch=master)](https://www.travis-ci.org/eddy8/lightCMS)    [![PHP Version](https://img.shields.io/badge/php-%3E%3D7.2-8892BF.svg)](http://www.php.net/)

## 项目简介
`lightCMS`是一个轻量级的`CMS`系统，也可以作为一个通用的后台管理框架使用。`lightCMS`集成了用户管理、权限管理、日志管理、菜单管理等后台管理框架的通用功能，同时也提供模型管理、分类管理等`CMS`系统中常用的功能。`lightCMS`的**代码一键生成**功能可以快速对特定模型生成增删改查代码，极大提高开发效率。

`lightCMS`基于`Laravel 6.x`开发，前端框架基于`layui`。

演示站点：[LightCMS Demo](http://lightcms.bituier.com/admin/login)。登录信息：admin/admin。请勿存储/删除重要数据，数据库会定时重置。

`LightCMS&Laravel`学习交流QQ群：**972796921**

版本库分支说明：

分支名称 | Laravel版本 | 备注
:-: | :-: | :-:
master    |   6.x | 建议使用
8.x    |   8.x |
7.x    |   7.x |
5.5    |   5.5 |

## 功能点一览
后台：
* 基于`RBAC`的权限管理
* 管理员、日志、菜单管理
* 分类管理
* 标签管理
* 配置管理
* 模型、模型字段、模型内容管理（后台可自定义业务模型，方便垂直行业快速开发）
* 会员管理
* 评论管理
* 基于Tire算法的敏感词过滤系统
* 普通模型增删改查代码一键生成

前台：
* 用户注册登录（包括微信、QQ、微博三方登录）
* 模型内容详情页、列表页
* 评论相关

更多功能待你发现~

## 后台预览
![首页](https://user-images.githubusercontent.com/2555476/54804611-16fa4900-4caf-11e9-885e-7f5c0dac7ce4.png)

![系统管理](https://user-images.githubusercontent.com/2555476/54804599-0ea20e00-4caf-11e9-8d10-526aca358916.png)

## 系统环境
`linux/windows & nginx/apache/iis & mysql 5.5+ & php 7.2+`

* PHP >= 7.2.0
* OpenSSL PHP Extension
* PDO PHP Extension
* Mbstring PHP Extension
* Tokenizer PHP Extension
* XML PHP Extension

**注意事项**

* 如果缓存、队列、session用的是 redis 驱动，那还需要安装 redis 和 php redis 扩展
* 如果`PHP`安装了`opcache`扩展，请启用`opcache.save_comments`和`opcache.load_comments`配置（默认是启用的），否则无法正常使用[菜单自动获取](#菜单自动获取)功能

## 系统部署

### 获取代码并安装依赖
首先请确保系统已安装好[composer](https://getcomposer.org/)。国内用户建议先[设置 composer 镜像](https://developer.aliyun.com/composer)，避免安装过程缓慢。
```bash
cd /data/www
git clone git_repository_url
cd lightCMS
composer install
```
### 系统配置并初始化
设置目录权限：`storage/`和`bootstrap/cache/`目录需要写入权限。
```bash
# 此处权限设置为777只是为了演示操作方便，实际只需要给web服务器写入权限即可
sudo chmod 777 -R storage/ bootstrap/cache/
```
新建一份环境配置，并配置好数据库等相关配置:
```base
cp .env.example .env
```
初始化系统：
```base
php artisan migrate --seed
```

### 配置Web服务器（此处以`Nginx`为例）
```
server {
    listen 80;
    server_name light.com;
    root /data/www/lightCMS/public;
    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /index.php {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        #不同配置对应不同的环境配置文件。比如此处应用会加载.env.pro文件，默认不配置会加载.env文件。此处可根据项目需要自行配制。
        #fastcgi_param   APP_ENV pro;
        include fastcgi_params;
    }
}
```

### 后台登陆
后台访问地址：`/admin/login`

默认用户（此用户为超级用户，不受权限管理限制）：admin/admin

## 权限管理
基于角色的权限管理。只需新建好角色，给对应的角色分配好相应的权限，最后给用户指定角色即可。`lightCMS`中权限的概念其实就是菜单，一条菜单对应一个`laravel`的路由，也就是一个具体的操作。

### 菜单自动获取
只需要按约定方式写好指定路由的控制器注释，则可在[菜单管理](/admin/menus)页面自动添加/更新对应的菜单信息。例如：
```php
/**
 * 角色管理-角色列表
 *
 * 取方法的第一行注释作为菜单的名称、分组名。注释格式：分组名称-菜单名称。
 * 未写分组名称，则直接作为菜单名称，分组名为空。
 * 未写注释则选用uri作为菜单名，分组名为空。
 */
public function index()
{
    $this->breadcrumb[] = ['title' => '角色列表', 'url' => ''];
    return view('admin.role.index', ['breadcrumb' => $this->breadcrumb]);
}
```

需要注意的是，程序可以自动获取菜单，但是菜单的层级关系还是需要在后台手动配置的。

## 配置管理
首先需要将`config/light.php`配置文件中的`light_config`设置为`true`：

然后只需在[配置管理](/admin/configs)页面新增配置项或编辑已存在配置项，则在应用中可以直接使用`laravel`的配置获取函数`config`获取指定配置，例如：
```php
// 获取 key 为 SITE_NAME 的配置项值
$siteName = config('light_config.SITE_NAME');
```
也可以直接调用全局函数`function getConfig($key, $default = null)`获取配置。

## 标签管理
模型内容**打标签**是站点的一项常用功能，`lightCMS`内置了打标签功能。添加模型字段时选择表单类型为`标签输入框`即可。

`lightCMS`采用中间表（content_tags）来实现标签和模型内容的多对多关联关系。

## 模型管理
`lightCMS`支持在后台直接创建模型，并可对模型的表字段进行自定义设置。设置完模型字段后，就不需要做其它工作了，模型的增删改查功能系统已经内置。

> 小提示：如果需要对单独的模型进行权限控制，可以在模型管理页面点击`添加默认菜单`，系统会自动建立好相应模型的相关菜单项。

这里说明下模型的表单验证及后端的保存和更新处理。如果有自定义表单验证需求，只需在`app/Http/Request/Admin/Entity`目录下创建模型的表单请求验证类即可。类名的命名规则：**模型名+Request**。例如`User`模型对应的表单请求验证类为`UserRequest`。

如果想自定义模型的新增/编辑前端模板，只需在`app/resources/views/admin/content`目录下创建模板文件即可。模板文件的命名需遵循如下命名规则：**模型名_add.blade.php**。例如`User`模型对应的模板文件名为`user_add.blade.php`。

如果想自定义模型的保存和更新处理逻辑，只需在`app/Http/Controllers/Admin/Entity`目录下创建模型的控制器类即可，`save`和`update`方法实现可参考`app/Http/Controllers/Admin/ContentController`。类名的命名规则：**模型名+Controller**。例如`User`模型对应的控制器类为`UserController`。同理，如果想自定义列表页，按上述规则定义`index`和`list`方法即可。

另外，模型内容在新增、更新、删除时系统会触发相应的事件，你可以监听这些事件做相应的业务处理。下表所示为相应的事件说明：

事件名 | 事件参数 | 触发时间 | 备注
:-: | :-: | :-: | :-:
App\Events\ContentCreating    |   Illuminate\Http\Request $request, App\Model\Admin\Entity $entity |  新增内容前  |
App\Events\ContentCreated    |   App\Model\Admin\Content $content, App\Model\Admin\Entity $entity |  新增内容后  |
App\Events\ContentUpdating    |   Illuminate\Http\Request $request, App\Model\Admin\Entity $entity |  更新内容前  |
App\Events\ContentUpdated    |   Array $id, App\Model\Admin\Entity $entity |  更新内容后  | $id 为更新内容的 ID 合集
App\Events\ContentDeleted    |   Illuminate\Support\Collection $contents, App\Model\Admin\Entity $entity |  删除内容后  | $contents 为被删除内容的 App\Model\Admin\Content 对象合集

### 模型字段表单类型相关说明
对于支持远程搜索的`select`表单类型，后端 API 搜索接口需返回的数据格式如下所示。code为0时, 表示正常, 反之异常。
```json
{
    "code": 0,
    "msg": "success",
    "data": [
        {"name":"北京","value":1,"selected":"","disabled":""},
        {"name":"上海","value":2,"selected":"","disabled":""},
        {"name":"广州","value":3,"selected":"selected","disabled":""},
        {"name":"深圳","value":4,"selected":"","disabled":"disabled"},
        {"name":"天津","value":5,"selected":"","disabled":""}
    ]
}
```

对于短文本（input，自动完成）表单类型，后端 API 接口需返回的数据格式如下所示：
```json
{
    "suggestions": [
        "cms",
        "cms是什么意思啊",
        "cms是指的什么意思啊",
        "cm是什么单位",
        "沉默是金",
        "cm是厘米还是分米",
        "cm是什么",
        "cm是什么意思啊",
        "cm是什么意思单位",
        "cm是什么单位的名称"
    ]
}
```

对于`select`多选类型表单，默认数据库保存值为半角逗号分隔的多个选择值。当你设置字段类型为无符号整型时，数据库会保存多个选择值的求和值（当然前提是选择值都是整型数据）。

### 搜索字段（$searchField）配置说明
通过配置搜索字段，可以很方便的在模型的列表页展示搜索项。如下是一个示例配置：
```php
    public static $searchField = [
        'name' => '用户名', // input搜索类型。key 为字段名称，value 为标题
        'status' => [ // key 为字段名称，value 为相关配置
            'showType' => 'select', // 下拉框选择搜索类型
            'searchType' => '=', // 说明字段在数据库的搜索匹配方式，默认为like查询
            'title' => '状态', // 标题
            'enums' => [ // select下拉搜索项
                0 => '禁用',
                1 => '启用',
            ],
        ],
        'recommend' => [ // key 为字段名称，value 为相关配置
            'showType' => 'select', // 下拉框选择搜索类型
            'searchType' => 'whereRaw', // 对于一些特殊的查询条件，无法通过上述普通的搜索匹配值来实现时，可将此值设置为 whereRaw
            'searchCondition' => 'recommend & ? = ?', // 与 whereRaw 配合使用，? 表示查询条件值参数绑定。例：如果用户输入的查询值为 2，则最终生成的 sql 查询条件是： recommend & 2 = 2
            'title' => '推荐位', // 标题
            'enums' => [ // select下拉搜索项
                1 => '推荐位1',
                2 => '推荐位2',
                4 => '推荐位3',
            ],
        ],
        'created_at' => [ // key 为字段名称，value 为相关配置
            'showType' => 'datetime', // 日期时间搜索类型
            'title' => '创建时间' // 标题
        ]
    ];
```

### 列表字段（$listField）配置说明
通过配置列表字段，可以很方便的在模型的列表页展示列表项。如下是一个示例配置：
```php
    public static $listField = [
        // pid 是列表字段名（不一定是模型数据库表的字段名，只要列表数据接口返回数据包含该字段即可）;title、width、sort 等属性参考 layui 的 table 组件表头参数配置即可
        'pid' => ['title' => '父ID', 'width' => 80],
        'entityName' => ['title' => '模型', 'width' => 100],
        'userName' => ['title' => '用户名', 'width' => 100],
        'content' => ['title' => '内容', 'width' => 400],
        'reply_count' => ['title' => '回复数', 'width' => 80, 'sort' => true],
        'like' => ['title' => '喜欢', 'width' => 80, 'sort' => true],
        'dislike' => ['title' => '不喜欢', 'width' => 80, 'sort' => true],
    ];
```

### 列表操作项（$actionField）配置说明
通过配置列表操作项，可以很方便的在模型的列表页操作列添加自定义链接。如下是一个示例配置：
```php
    public static $actionField = [
        // chapterUrl 是字段名（不一定是模型数据库表的字段名，只要列表数据接口返回数据包含该字段即可）
        'chapterUrl' => ['title' => '章节', 'description' => '当前小说的所有章节'],
    ];
```

### 排序字段（$sortFields）配置说明
通过配置排序字段，可以很方便的在模型的列表页自定义数据的排序规则。如下是一个示例配置：
```php
    public static $actionField = [
        // 数组的键为排序字段名和升序/降序配置（半角逗号分隔），值为前台展示名称
        'updated_at,desc' => '更新时间（降序）',
        'id,asc' => 'id（升序）',
    ];
```

> 小提示：如果你是自定义模型，建议自定义模型继承`App\Model\Admin\Model`模型，方便对上述配置项进行自定义。

## 系统日志
`lightCMS`集成了一套简单的日志系统，默认情况下记录后台的所有操作相关信息，具体实现可以参考`Log`中间件。

可以利用`Laravel`的[任务调度](https://laravel.com/docs/5.8/scheduling#introduction)来自动清理系统日志。启用任务调度需要在系统的计划任务中添加如下内容：
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

可以通过配置`log_async_write`项来决定是否启用异步写入日志（默认未启用），异步写入日志需要运行`Laravel`的[队列处理器](https://laravel.com/docs/5.8/queues#running-the-queue-worker)：
```bash
php artisan queue:work
```

## 代码一键生成
对于一个普通的模型，管理后台通常有增删改查相关的业务需求。如果系统模型管理自带的增删改查功能无法满足你的个性化需求，你可以使用一键生成代码功能。`lightCMS`拥有一键生成相关代码的能力，在建好模型的数据库表结构后，可以使用如下`artisan`命令生成相关代码：
```bash
# config 为模型名称 配置 为模型中文名称
php artisan light:basic config 配置
```
成功执行完成后，会创建如下文件（注意：相关目录需要有写入权限）：

* routes/auto/config.php
路由：包含模型增删改查相关路由，应用会自动加载`routes/auto/`目录下的路由。
* app/Model/Admin/Config.php
模型：[$searchField](#搜索字段searchField配置说明) 属性用来配置搜索字段，[$listField](#列表字段listfield配置说明) 用来配置列表视图中需要展示哪些字段数据。
* app/Repository/Admin/ConfigRepository.php
模型服务层：默认有一个`list`方法，该方法用来返回列表数据。需要注意的是如果列表中的数据不能和数据库字段数据直接对应，则可对数据库字段数据做相应转换，可参考`list`方法中的`transform`部分。
* app/Http/Controllers/Admin/ConfigController.php
控制器：默认有一个`$formNames`属性，用来配置新增/编辑表单请求字段的白名单。此属性必需配置，否则获取不到表单数据。参考 [request 对象的 only 方法](https://laravel.com/docs/5.5/requests#retrieving-input)
* app/Http/Requests/Admin/ConfigRequest.php
表单请求类：可在此类中编写表单验证规则，参考 [Form Request Validation](https://laravel.com/docs/5.5/validation#form-request-validation)
* resources/views/admin/config/index.blade.php
列表视图：列表数据、搜索表单。
* resources/views/admin/config/index.blade.php
新增/编辑视图：只列出了基本架构，需要自定义相关字段的表单展示。参考 [layui form](https://www.layui.com/doc/element/form.html)

最后，如果想让生成的路由展示在菜单中，只需在[菜单管理](/admin/menus)页面点击**自动更新菜单**即可。

## 敏感词检测
如果需要对发表的内容（文章、评论等）进行内容审查，则可直接调用`LightCMS`提供的`checkSensitiveWords`函数即可。示例如下：
```php
$result = checkSensitiveWords('出售正品枪支');
print_r($result);
/*
[
    "售 出售 枪",
    "正品枪支"
]
*/
```

## 图片上传
LightCMS中图片默认上传到本地服务器。如果有自定义需求，比如上传到三方云服务器，可参考`config/light.php`配置文件中的`image_upload`配置项说明，自定义处理类需要实现`App\Contracts\ImageUpload`接口，方法的返回值数据结构和系统原方法保持一致即可。
```json
{
    "code": 200,
    "state": "SUCCESS",
    "msg": "",
    "url": "xxx"
}
```

## 系统核心函数、方法说明
做这个说明的主要目的是让开发者了解一些核心功能，方便自定义各类功能开发。毕竟框架是不可能代劳所有事情滴^_

方法名称：App\Repository\Admin\CategoryRepository::tree()

功能说明：

返回分类的树结构信息。数据结构可以参考下图所示：

![tree](https://user-images.githubusercontent.com/2555476/62991339-d7acde80-be81-11e9-9811-9d4d27e01f07.png)

此数据结构基本包含了分类的所有结构化信息。相关字段的含义也比较清楚，此处只对`path`字段做下说明：该字段是指当前分类的所有上级分类链，这样可以很方便的知道某个分类的所有父级分类。比如图中的`test`分类的path字段值为`[1, 2]`，那么很容易的知道它的父级分类是：游戏 射击

## 前台相关
### 用户注册登录
`LightCMS`集成了一套简单的用户注册登录系统，支持微信、QQ、微博三方登录。三方登录相关配置请参考`config/light.php`。

## TODO
* 模版管理+模版标签

## 完善中。。。

## 说明
有问题可以提 issue ，为项目贡献代码可以提 pull request
