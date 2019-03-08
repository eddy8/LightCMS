<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->default('');
            $table->unsignedInteger('pid')->default(0);
            $table->unsignedInteger('model_id')->default(0);
            $table->unsignedInteger('order')->default(0);
            $table->string('title')->default('');
            $table->string('keywords')->default('');
            $table->string('description')->default('');
            $table->timestamps();

            $table->unique(['pid', 'name', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
