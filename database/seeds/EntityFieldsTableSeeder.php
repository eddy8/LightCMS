<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntityFieldsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = <<<EOL
INSERT INTO `entity_fields` (`id`, `name`, `type`, `comment`, `form_name`, `form_type`, `form_comment`, `form_params`, `is_show`, `is_edit`, `is_required`, `entity_id`, `order`, `created_at`, `updated_at`) VALUES ('4', 'title', 'string', '标题', '标题', 'input', '', '', '1', '1', '1', '2', '77', '2019-03-15 09:52:32', '2019-03-15 09:52:32');
INSERT INTO `entity_fields` (`id`, `name`, `type`, `comment`, `form_name`, `form_type`, `form_comment`, `form_params`, `is_show`, `is_edit`, `is_required`, `entity_id`, `order`, `created_at`, `updated_at`) VALUES ('5', 'content', 'text', '正文', '正文', 'richText', '', '', '1', '1', '1', '2', '100', '2019-03-15 09:53:35', '2019-03-16 09:40:39');
INSERT INTO `entity_fields` (`id`, `name`, `type`, `comment`, `form_name`, `form_type`, `form_comment`, `form_params`, `is_show`, `is_edit`, `is_required`, `entity_id`, `order`, `created_at`, `updated_at`) VALUES ('6', 'category_id', 'unsignedInteger', '分类ID', '分类', 'reference_category', '', '', '1', '1', '1', '2', '77', '2019-03-15 10:19:15', '2019-03-15 10:19:15');
INSERT INTO `entity_fields` (`id`, `name`, `type`, `comment`, `form_name`, `form_type`, `form_comment`, `form_params`, `is_show`, `is_edit`, `is_required`, `entity_id`, `order`, `created_at`, `updated_at`) VALUES ('7', 'admin_user_id', 'unsignedInteger', '作者', '作者', 'reference_admin_user', '', '', '1', '1', '1', '2', '77', '2019-03-15 10:24:50', '2019-03-15 10:24:50');
INSERT INTO `entity_fields` (`id`, `name`, `type`, `comment`, `form_name`, `form_type`, `form_comment`, `form_params`, `is_show`, `is_edit`, `is_required`, `entity_id`, `order`, `created_at`, `updated_at`) VALUES ('8', 'introduction', 'string', '简介', '简介', 'textArea', '', '', '1', '1', '1', '2', '77', '2019-03-15 15:07:28', '2019-03-15 15:07:28');
INSERT INTO `entity_fields` (`id`, `name`, `type`, `comment`, `form_name`, `form_type`, `form_comment`, `form_params`, `is_show`, `is_edit`, `is_required`, `entity_id`, `order`, `created_at`, `updated_at`) VALUES ('9', 'keyword', 'string', '关键字', '关键字', 'input', '', '', '1', '0', '1', '2', '77', '2019-03-16 11:24:43', '2019-03-20 09:24:42');
INSERT INTO `entity_fields` (`id`, `name`, `type`, `comment`, `form_name`, `form_type`, `form_comment`, `form_params`, `is_show`, `is_edit`, `is_required`, `entity_id`, `order`, `created_at`, `updated_at`) VALUES ('11', 'cover_image', 'string', '封面图', '封面图', 'upload', '', '', '1', '1', '1', '2', '77', '2019-03-16 15:50:25', '2019-03-20 09:07:52');
INSERT INTO `entity_fields` (`id`, `name`, `type`, `comment`, `form_name`, `form_type`, `form_comment`, `form_params`, `is_show`, `is_edit`, `is_required`, `entity_id`, `order`, `created_at`, `updated_at`) VALUES ('12', 'is_publish', 'unsignedTinyInteger', '是否已发布', '发布', 'option', '', '0=未发布\n1=已发布', '1', '1', '1', '2', '77', '2019-03-19 11:18:22', '2019-03-19 11:49:18');
INSERT INTO `entity_fields` (`id`, `name`, `type`, `comment`, `form_name`, `form_type`, `form_comment`, `form_params`, `is_show`, `is_edit`, `is_required`, `entity_id`, `order`, `created_at`, `updated_at`) VALUES ('13', 'recommend', 'string', '', '推荐', 'checkbox', '', 'index=首页\nlist=列表页\nkeyword=K页面\nspecial=专题页', '1', '0', '1', '2', '77', '2019-03-19 13:37:27', '2019-03-20 09:15:22');
INSERT INTO `entity_fields` (`id`, `name`, `type`, `comment`, `form_name`, `form_type`, `form_comment`, `form_params`, `is_show`, `is_edit`, `is_required`, `entity_id`, `order`, `created_at`, `updated_at`) VALUES ('14', 'title_color', 'string', '', '标题颜色', 'select', '', '=默认\nred=红色\nblue=蓝色\nyellow=黄色', '1', '1', '1', '2', '77', '2019-03-19 14:01:42', '2019-03-20 08:41:02');
INSERT INTO `entity_fields` (`id`, `name`, `type`, `comment`, `form_name`, `form_type`, `form_comment`, `form_params`, `is_show`, `is_edit`, `is_required`, `entity_id`, `order`, `created_at`, `updated_at`) VALUES ('15', 'toutiao', 'text', '', '头条', 'richText', '', '', '1', '1', '1', '2', '77', '2019-03-20 08:55:51', '2019-03-20 08:55:51');
EOL;
        DB::unprepared($sql);
    }
}
