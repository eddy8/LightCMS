## 权限管理
基于角色的权限管理。只需新建好角色，给对应的角色分配好相应的权限，最后给用户指定角色即可。`lightCMS`中权限的概念其实就是菜单，一条菜单对应一个`laravel`的路由，也就是一个具体的操作。

## 菜单自动获取
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

## 自定义配置获取
只需在[配置管理](/admin/configs)页面新增配置项或编辑已存在配置项，则在应用中可以直接使用`laravel`的配置获取函数`config`获取指定配置，例如：
```php
// 获取 key 为 SITE_NAME 的配置项值
$siteName = config('light_config.SITE_NAME');
```

## 系统日志

## 完善中。。。