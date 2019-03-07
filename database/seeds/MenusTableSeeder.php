<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = <<<EOL
INSERT INTO `menus` VALUES ('23', '用户登录页面', '0', '0', '1', 'admin::login.show', '/admin/login', '基础功能', 'admin', '', '2019-02-28 12:50:35', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('24', '用户登录', '0', '0', '1', 'admin::login', '/admin/login', '基础功能', 'admin', '', '2019-02-28 12:50:35', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('25', '退出登录', '0', '0', '1', 'admin::logout', '/admin/logout', '基础功能', 'admin', '', '2019-02-28 12:50:35', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('26', '首页', '0', '1', '1', 'admin::index', '/admin/index', '', 'admin', '', '2019-02-28 12:50:35', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('27', '管理员列表', '40', '1', '1', 'admin::adminUser.index', '/admin/admin_users', '管理员管理', 'admin', '', '2019-02-28 12:50:35', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('28', '管理员列表数据', '40', '0', '2', 'admin::adminUser.list', '/admin/admin_users/list', '管理员管理', 'admin', '', '2019-02-28 12:50:35', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('29', '新增管理员', '40', '1', '2', 'admin::adminUser.create', '/admin/admin_users/create', '管理员管理', 'admin', '', '2019-02-28 12:50:35', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('30', '保存管理员', '40', '0', '1', 'admin::adminUser.save', '/admin/admin_users', '管理员管理', 'admin', '', '2019-02-28 12:50:35', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('31', '编辑管理员', '40', '0', '2', 'admin::adminUser.edit', '', '管理员管理', 'admin', '', '2019-02-28 12:50:35', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('32', '更新管理员', '40', '0', '2', 'admin::adminUser.update', '', '管理员管理', 'admin', '', '2019-02-28 12:50:35', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('33', '菜单列表', '40', '1', '1', 'admin::menu.index', '/admin/menus', '菜单管理', 'admin', '', '2019-02-28 12:50:35', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('34', '菜单列表数据', '40', '0', '1', 'admin::menu.list', '/admin/menus/list', '菜单管理', 'admin', '', '2019-02-28 12:50:35', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('35', '新增菜单', '40', '1', '2', 'admin::menu.create', '/admin/menus/create', '菜单管理', 'admin', '', '2019-02-28 12:50:35', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('36', '保存菜单', '40', '0', '1', 'admin::menu.save', '/admin/menus', '菜单管理', 'admin', '', '2019-02-28 12:50:35', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('37', '编辑菜单', '40', '0', '2', 'admin::menu.edit', '', '菜单管理', 'admin', '', '2019-02-28 12:50:35', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('38', '更新菜单', '40', '0', '1', 'admin::menu.update', '', '菜单管理', 'admin', '', '2019-02-28 12:50:35', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('40', '系统管理', '0', '1', '1', 'admin::config.index', '/admin/configs', '', 'admin', '', null, '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('41', '自动更新菜单', '40', '0', '1', 'admin::menu.discovery', '/admin/menus/discovery', '菜单管理', 'admin', '', '2019-02-28 15:36:34', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('43', '角色列表', '40', '1', '1', 'admin::role.index', '/admin/roles', '角色管理', 'admin', '', '2019-03-01 14:17:26', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('44', '角色列表数据接口', '40', '0', '1', 'admin::role.list', '/admin/roles/list', '角色管理', 'admin', '', '2019-03-01 14:17:26', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('45', '新增角色', '40', '1', '2', 'admin::role.create', '/admin/roles/create', '角色管理', 'admin', '', '2019-03-01 14:17:26', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('46', '保存角色', '40', '0', '1', 'admin::role.save', '/admin/roles', '角色管理', 'admin', '', '2019-03-01 14:17:26', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('47', '编辑角色', '40', '0', '1', 'admin::role.edit', '', '角色管理', 'admin', '', '2019-03-01 14:17:26', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('48', '更新角色', '40', '0', '1', 'admin::role.update', '', '角色管理', 'admin', '', '2019-03-01 14:17:26', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('49', '分配角色', '40', '0', '1', 'admin::adminUser.role.edit', '', '管理员管理', 'admin', '', '2019-03-01 16:54:43', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('50', '更新角色', '40', '0', '1', 'admin::adminUser.role.update', '', '管理员管理', 'admin', '', '2019-03-01 16:54:43', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('51', '分配权限', '40', '0', '1', 'admin::role.permission.edit', '', '角色管理', 'admin', '', '2019-03-02 12:04:28', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('52', '更新权限', '40', '0', '1', 'admin::role.permission.update', '', '角色管理', 'admin', '', '2019-03-02 12:04:28', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('53', '配置列表数据接口', '40', '0', '1', 'admin::config.list', '/admin/configs/list', '配置管理', 'admin', '', '2019-03-04 12:09:17', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('54', '新增配置', '40', '1', '7', 'admin::config.create', '/admin/configs/create', '配置管理', 'admin', '', '2019-03-04 12:09:17', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('55', '保存配置', '40', '0', '1', 'admin::config.save', '/admin/configs', '配置管理', 'admin', '', '2019-03-04 12:09:17', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('56', '编辑配置', '40', '0', '1', 'admin::config.edit', '', '配置管理', 'admin', '', '2019-03-04 12:09:17', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('57', '更新配置', '40', '0', '1', 'admin::config.update', '', '配置管理', 'admin', '', '2019-03-04 12:09:17', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('58', '日志列表', '40', '1', '10', 'admin::log.index', '/admin/logs', '日志管理', 'admin', '', '2019-03-06 09:53:46', '2019-03-07 09:45:18');
INSERT INTO `menus` VALUES ('59', '日志列表数据接口', '40', '0', '1', 'admin::log.list', '/admin/logs/list', '', 'admin', '', '2019-03-06 09:53:46', '2019-03-07 09:45:18');
INSERT INTO `menus` VALUES ('61', '批量操作', '40', '0', '1', 'admin::menu.batch', '/admin/menus/batch', '菜单管理', 'admin', '', '2019-03-06 16:03:14', '2019-03-07 09:45:17');
INSERT INTO `menus` VALUES ('62', '百度', '0', '1', '4', 'baidu', 'https://www.baidu.com', '', 'admin', '', '2019-03-06 17:01:30', '2019-03-07 08:41:52');
INSERT INTO `menus` VALUES ('64', '删除菜单', '40', '0', '1', 'admin::menu.delete', '', '菜单管理', 'admin', '', '2019-03-07 09:45:17', '2019-03-07 09:45:43');
EOL;
        DB::unprepared($sql);
    }
}
