<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = <<<EOL
DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '标题',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '正文',
  `admin_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '作者',
  `category_id` int(10) unsigned NOT NULL COMMENT '分类',
  `introduction` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '简介',
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '关键字',
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '封面图',
  `is_publish` tinyint(3) unsigned NOT NULL COMMENT '是否已发布',
  `recommend` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `title_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `toutiao` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `entities` (`id`, `name`, `table_name`, `description`, `created_at`, `updated_at`) VALUES ('2', '文章', 'articles', '博客文章表', '2019-03-08 15:20:15', '2019-03-15 09:51:14');
EOL;
        DB::unprepared($sql);
    }
}
