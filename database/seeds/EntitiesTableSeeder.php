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
INSERT INTO `entities` (`id`, `name`, `table_name`, `description`, `created_at`, `updated_at`) VALUES ('2', '文章', 'articles', '博客文章表', '2019-03-08 15:20:15', '2019-03-15 09:51:14');
EOL;
        DB::unprepared($sql);
    }
}
