## 配置
只需在[配置管理](http://light.sjh.jf95.com/admin/configs)页面新增配置项或编辑已存在配置项，则在应用中可以直接使用`laravel`的配置获取函数`config`获取指定配置，例如：
```php
// 获取 key 为 SITE_NAME 的配置项值
$siteName = config('light_config.SITE_NAME');
```