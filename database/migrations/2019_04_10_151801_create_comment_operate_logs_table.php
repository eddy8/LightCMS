<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentOperateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_operate_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('comment_id')->default(0);
            $table->string('operate', 20)->default('');
            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_operate_logs');
    }
}
