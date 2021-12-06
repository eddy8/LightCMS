<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryEntityIdToEntityFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entity_fields', function (Blueprint $table) {
            $table->unsignedInteger('category_entity_id')
                ->nullable(false)->default(0)->comment('引用分类数据的模型ID');
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
            $table->dropColumn('category_entity_id');
        });
    }
}
