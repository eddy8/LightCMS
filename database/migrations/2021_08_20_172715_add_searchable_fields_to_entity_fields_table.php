<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSearchableFieldsToEntityFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entity_fields', function (Blueprint $table) {
            $table->string('search_type', 20)->nullable(false)->default('like')->comment('数据库搜索方式');
            $table->string('show_type', 20)->nullable(false)->default('input')->comment('表单展示搜索方式');
            $table->string('search_params', 512)->nullable(false)->default('')->comment('搜索参数');
            $table->unsignedTinyInteger('is_enable_search')->nullable(false)->default(0)->comment('是否启用搜索');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entity_fields', function (Blueprint $table) {
            $table->dropColumn(['search_type', 'show_type', 'search_params', 'is_enable_search']);
        });
    }
}
