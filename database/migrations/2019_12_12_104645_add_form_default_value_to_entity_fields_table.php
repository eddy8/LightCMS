<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFormDefaultValueToEntityFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entity_fields', function (Blueprint $table) {
            $table->string('form_default_value')->default('')->comment('字段表单默认值');
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
            $table->dropColumn('form_default_value');
        });
    }
}
