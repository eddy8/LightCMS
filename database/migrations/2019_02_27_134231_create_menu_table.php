<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->default('');
            $table->unsignedInteger('pid')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger('order')->default(1);
            $table->string('route', 100)->default('');
            $table->string('group', 50)->default('');
            $table->string('remark')->default('');
            $table->timestamps();

            $table->unique(['route', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
