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
INSERT INTO `menus` VALUES ('23', '用户登录页面', '0', '0', '1', 'admin::login.show', '/admin/login', '基础功能', 'admin', '', '0', '2019-02-28 12:50:35', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('24', '用户登录', '0', '0', '1', 'admin::login', '/admin/login', '基础功能', 'admin', '', '0', '2019-02-28 12:50:35', '2019-03-08 10:35:50');
INSERT INTO `menus` VALUES ('26', '首页', '0', '1', '1', 'admin::index', '/admin/index', '', 'admin', '', '0', '2019-02-28 12:50:35', '2019-03-08 10:50:16');
INSERT INTO `menus` VALUES ('27', '管理员列表', '40', '1', '1', 'admin::adminUser.index', '/admin/admin_users', '管理员管理', 'admin', '', '0', '2019-02-28 12:50:35', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('28', '管理员列表数据', '40', '0', '2', 'admin::adminUser.list', '/admin/admin_users/list', '管理员管理', 'admin', '', '0', '2019-02-28 12:50:35', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('29', '新增管理员', '40', '1', '2', 'admin::adminUser.create', '/admin/admin_users/create', '管理员管理', 'admin', '', '0', '2019-02-28 12:50:35', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('30', '保存管理员', '40', '0', '1', 'admin::adminUser.save', '/admin/admin_users', '管理员管理', 'admin', '', '0', '2019-02-28 12:50:35', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('31', '编辑管理员', '40', '0', '2', 'admin::adminUser.edit', '', '管理员管理', 'admin', '', '0', '2019-02-28 12:50:35', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('32', '更新管理员', '40', '0', '2', 'admin::adminUser.update', '', '管理员管理', 'admin', '', '0', '2019-02-28 12:50:35', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('33', '菜单列表', '40', '1', '1', 'admin::menu.index', '/admin/menus', '菜单管理', 'admin', '', '0', '2019-02-28 12:50:35', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('34', '菜单列表数据', '40', '0', '1', 'admin::menu.list', '/admin/menus/list', '菜单管理', 'admin', '', '0', '2019-02-28 12:50:35', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('35', '新增菜单', '40', '1', '2', 'admin::menu.create', '/admin/menus/create', '菜单管理', 'admin', '', '0', '2019-02-28 12:50:35', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('36', '保存菜单', '40', '0', '1', 'admin::menu.save', '/admin/menus', '菜单管理', 'admin', '', '0', '2019-02-28 12:50:35', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('37', '编辑菜单', '40', '0', '2', 'admin::menu.edit', '', '菜单管理', 'admin', '', '0', '2019-02-28 12:50:35', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('38', '更新菜单', '40', '0', '1', 'admin::menu.update', '', '菜单管理', 'admin', '', '0', '2019-02-28 12:50:35', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('40', '系统管理', '0', '1', '1', 'admin::config.index', '/admin/configs', '', 'admin', '', '1', null, '2019-03-21 17:07:49');
INSERT INTO `menus` VALUES ('41', '自动更新菜单', '40', '0', '1', 'admin::menu.discovery', '/admin/menus/discovery', '菜单管理', 'admin', '', '0', '2019-02-28 15:36:34', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('43', '角色列表', '40', '1', '1', 'admin::role.index', '/admin/roles', '角色管理', 'admin', '', '0', '2019-03-01 14:17:26', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('44', '角色列表数据接口', '40', '0', '1', 'admin::role.list', '/admin/roles/list', '角色管理', 'admin', '', '0', '2019-03-01 14:17:26', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('45', '新增角色', '40', '1', '2', 'admin::role.create', '/admin/roles/create', '角色管理', 'admin', '', '0', '2019-03-01 14:17:26', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('46', '保存角色', '40', '0', '1', 'admin::role.save', '/admin/roles', '角色管理', 'admin', '', '0', '2019-03-01 14:17:26', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('47', '编辑角色', '40', '0', '1', 'admin::role.edit', '', '角色管理', 'admin', '', '0', '2019-03-01 14:17:26', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('48', '更新角色', '40', '0', '1', 'admin::role.update', '', '角色管理', 'admin', '', '0', '2019-03-01 14:17:26', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('49', '分配角色', '40', '0', '1', 'admin::adminUser.role.edit', '', '管理员管理', 'admin', '', '0', '2019-03-01 16:54:43', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('50', '更新用户角色', '40', '0', '1', 'admin::adminUser.role.update', '', '管理员管理', 'admin', '', '0', '2019-03-01 16:54:43', '2019-03-21 16:37:32');
INSERT INTO `menus` VALUES ('51', '分配权限', '40', '0', '1', 'admin::role.permission.edit', '', '角色管理', 'admin', '', '0', '2019-03-02 12:04:28', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('52', '更新权限', '40', '0', '1', 'admin::role.permission.update', '', '角色管理', 'admin', '', '0', '2019-03-02 12:04:28', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('53', '配置列表数据接口', '40', '0', '1', 'admin::config.list', '/admin/configs/list', '配置管理', 'admin', '', '0', '2019-03-04 12:09:17', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('54', '新增配置', '40', '1', '7', 'admin::config.create', '/admin/configs/create', '配置管理', 'admin', '', '0', '2019-03-04 12:09:17', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('55', '保存配置', '40', '0', '1', 'admin::config.save', '/admin/configs', '配置管理', 'admin', '', '0', '2019-03-04 12:09:17', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('56', '编辑配置', '40', '0', '1', 'admin::config.edit', '', '配置管理', 'admin', '', '0', '2019-03-04 12:09:17', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('57', '更新配置', '40', '0', '1', 'admin::config.update', '', '配置管理', 'admin', '', '0', '2019-03-04 12:09:17', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('58', '日志列表', '40', '1', '10', 'admin::log.index', '/admin/logs', '日志管理', 'admin', '', '0', '2019-03-06 09:53:46', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('59', '日志列表数据接口', '40', '0', '1', 'admin::log.list', '/admin/logs/list', '', 'admin', '', '0', '2019-03-06 09:53:46', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('61', '批量操作', '40', '0', '1', 'admin::menu.batch', '/admin/menus/batch', '菜单管理', 'admin', '', '0', '2019-03-06 16:03:14', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('62', 'Google', '0', '1', '4', 'google', 'https://www.google.com', '', 'admin', '', '0', '2019-03-06 17:01:30', '2019-03-16 11:34:30');
INSERT INTO `menus` VALUES ('64', '删除菜单', '40', '0', '1', 'admin::menu.delete', '', '菜单管理', 'admin', '', '0', '2019-03-07 09:45:17', '2019-03-07 16:52:51');
INSERT INTO `menus` VALUES ('79', '分类列表', '26', '1', '20', 'admin::category.index', '/admin/categories', '分类管理', 'admin', '', '0', '2019-03-08 08:57:30', '2019-03-15 10:30:40');
INSERT INTO `menus` VALUES ('80', '分类列表数据接口', '26', '0', '1', 'admin::category.list', '/admin/categories/list', '分类管理', 'admin', '', '0', '2019-03-08 08:57:30', '2019-03-08 09:07:41');
INSERT INTO `menus` VALUES ('81', '新增分类', '26', '1', '21', 'admin::category.create', '/admin/categories/create', '分类管理', 'admin', '', '0', '2019-03-08 08:57:30', '2019-03-15 10:30:28');
INSERT INTO `menus` VALUES ('82', '保存分类', '26', '0', '1', 'admin::category.save', '/admin/categories', '分类管理', 'admin', '', '0', '2019-03-08 08:57:30', '2019-03-08 09:07:11');
INSERT INTO `menus` VALUES ('83', '编辑分类', '26', '0', '1', 'admin::category.edit', '', '分类管理', 'admin', '', '0', '2019-03-08 08:57:30', '2019-03-08 09:07:11');
INSERT INTO `menus` VALUES ('84', '更新分类', '26', '0', '1', 'admin::category.update', '', '分类管理', 'admin', '', '0', '2019-03-08 08:57:30', '2019-03-08 09:07:11');
INSERT INTO `menus` VALUES ('85', '退出登录', '0', '0', '1', 'admin::logout', '/admin/logout', '基础功能', 'admin', '', '0', '2019-03-08 10:50:34', '2019-03-08 10:50:53');
INSERT INTO `menus` VALUES ('86', '模型列表', '26', '1', '0', 'admin::entity.index', '/admin/entities', '模型管理', 'admin', '', '0', '2019-03-08 11:57:54', '2019-03-08 14:24:23');
INSERT INTO `menus` VALUES ('87', '模型列表数据接口', '26', '0', '1', 'admin::entity.list', '/admin/entities/list', '', 'admin', '', '0', '2019-03-08 11:57:54', '2019-03-08 11:58:50');
INSERT INTO `menus` VALUES ('88', '新增模型', '26', '1', '1', 'admin::entity.create', '/admin/entities/create', '模型管理', 'admin', '', '0', '2019-03-08 11:57:54', '2019-03-08 11:58:31');
INSERT INTO `menus` VALUES ('89', '保存模型', '26', '0', '1', 'admin::entity.save', '/admin/entities', '模型管理', 'admin', '', '0', '2019-03-08 11:57:54', '2019-03-08 11:58:31');
INSERT INTO `menus` VALUES ('90', '编辑模型', '26', '0', '1', 'admin::entity.edit', '', '模型管理', 'admin', '', '0', '2019-03-08 11:57:54', '2019-03-08 11:58:31');
INSERT INTO `menus` VALUES ('91', '更新模型', '26', '0', '1', 'admin::entity.update', '', '模型管理', 'admin', '', '0', '2019-03-08 11:57:54', '2019-03-08 11:58:31');
INSERT INTO `menus` VALUES ('92', '删除模型', '26', '0', '1', 'admin::entity.delete', '', '模型管理', 'admin', '', '0', '2019-03-08 11:57:54', '2019-03-08 11:58:31');
INSERT INTO `menus` VALUES ('93', '模型字段列表', '26', '1', '10', 'admin::entityField.index', '/admin/entityFields', '模型字段管理', 'admin', '', '0', '2019-03-08 14:10:31', '2019-03-12 13:37:19');
INSERT INTO `menus` VALUES ('94', '模型字段列表数据接口', '26', '0', '1', 'admin::entityField.list', '/admin/entityFields/list', '', 'admin', '', '0', '2019-03-08 14:10:31', '2019-03-08 14:11:20');
INSERT INTO `menus` VALUES ('95', '新增模型字段', '26', '1', '11', 'admin::entityField.create', '/admin/entityFields/create', '模型字段管理', 'admin', '', '0', '2019-03-08 14:10:31', '2019-03-12 13:37:39');
INSERT INTO `menus` VALUES ('96', '保存模型字段', '26', '0', '1', 'admin::entityField.save', '/admin/entityFields', '模型字段管理', 'admin', '', '0', '2019-03-08 14:10:31', '2019-03-08 14:11:08');
INSERT INTO `menus` VALUES ('97', '编辑模型字段', '26', '0', '1', 'admin::entityField.edit', '', '模型字段管理', 'admin', '', '0', '2019-03-08 14:10:31', '2019-03-08 14:11:08');
INSERT INTO `menus` VALUES ('98', '更新模型字段', '26', '0', '1', 'admin::entityField.update', '', '模型字段管理', 'admin', '', '0', '2019-03-08 14:10:31', '2019-03-08 14:11:08');
INSERT INTO `menus` VALUES ('99', '删除模型字段', '26', '0', '1', 'admin::entityField.delete', '', '模型字段管理', 'admin', '', '0', '2019-03-08 14:10:31', '2019-03-08 14:11:08');
INSERT INTO `menus` VALUES ('103', '保存内容', '0', '0', '1', 'admin::content.save', '/admin/contents', '内容管理', 'admin', '', '0', '2019-03-15 10:33:49', '2019-03-15 10:33:49');
INSERT INTO `menus` VALUES ('104', '编辑内容', '0', '0', '1', 'admin::content.edit', '', '内容管理', 'admin', '', '0', '2019-03-15 10:33:49', '2019-03-15 10:33:49');
INSERT INTO `menus` VALUES ('105', '更新内容', '0', '0', '1', 'admin::content.update', '', '内容管理', 'admin', '', '0', '2019-03-15 10:33:49', '2019-03-15 10:33:49');
INSERT INTO `menus` VALUES ('106', '删除内容', '0', '0', '1', 'admin::content.delete', '', '内容管理', 'admin', '', '0', '2019-03-15 10:33:49', '2019-03-15 10:33:49');
INSERT INTO `menus` VALUES ('107', '内容管理', '0', '1', '1', 'admin::aggregation', '/admin/aggregation', '', 'admin', '', '0', '2019-03-15 10:36:29', '2019-03-15 10:36:29');
INSERT INTO `menus` VALUES ('108', '内容列表', '0', '0', '1', 'admin::content.index', '', '内容管理', 'admin', '', '0', '2019-03-16 11:34:06', '2019-03-16 11:34:06');
INSERT INTO `menus` VALUES ('109', '内容列表数据接口', '0', '0', '1', 'admin::content.list', '', '', 'admin', '', '0', '2019-03-16 11:34:06', '2019-03-16 11:34:06');
INSERT INTO `menus` VALUES ('110', '新增内容', '0', '0', '1', 'admin::content.create', '', '内容管理', 'admin', '', '0', '2019-03-16 11:34:06', '2019-03-16 11:34:06');
INSERT INTO `menus` VALUES ('111', '图片上传', '0', '0', '1', 'admin::neditor.serve', '', '基础功能', 'admin', '', '0', '2019-03-21 16:37:32', '2019-03-21 16:40:36');
EOL;
        DB::unprepared($sql);
    }
}
