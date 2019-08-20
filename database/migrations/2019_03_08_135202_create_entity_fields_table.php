<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntityFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 64)->default('');
            $table->string('type')->default('');
            $table->string('comment', 100)->default('');
            $table->string('default_value')->default('');
            $table->string('form_name', 20)->default('');
            $table->string('form_type')->default('');
            $table->string('form_comment', 100)->default('');
            $table->string('form_params', 1024)->default('');
            $table->unsignedTinyInteger('is_show')->default(1);
            $table->unsignedTinyInteger('is_show_inline')->default(0);
            $table->unsignedTinyInteger('is_edit')->default(1);
            $table->unsignedTinyInteger('is_required')->default(0);
            $table->unsignedInteger('entity_id')->default(0);
            $table->unsignedInteger('order')->default(77);
            $table->timestamps();

            $table->unique(['entity_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entity_fields');
    }
}
