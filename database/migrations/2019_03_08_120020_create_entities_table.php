<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->default('');
            $table->string('table_name', 64)->default('')->unique();
            $table->string('description')->default('');
            $table->unsignedInteger('is_internal')->default(0);
            $table->unsignedInteger('enable_comment')->default(0);
            $table->unsignedTinyInteger('is_show_content_manage')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entities');
    }
}
