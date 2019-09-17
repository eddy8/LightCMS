<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminUsersTableSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(EntitiesTableSeeder::class);
        $this->call(EntityFieldsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(SensitiveWordsTableSeeder::class);
        $this->call(ConfigsTableSeeder::class);
    }
}
