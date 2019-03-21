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
            $table->string('name', 50)->default('')->unique();
            $table->unsignedInteger('pid')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('is_lock_name')->default(0);
            $table->unsignedInteger('order')->default(0);
            $table->string('route', 100)->default('')->unique();
            $table->string('url', 512)->default('');
            $table->string('group', 50)->default('');
            $table->string('guard_name', 50)->default('admin');
            $table->string('remark')->default('');
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
        Schema::dropIfExists('menus');
    }
}
