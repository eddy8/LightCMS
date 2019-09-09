<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedTinyInteger('is_admin')->default(0);
            $table->unsignedInteger('entity_id')->default(0);
            $table->unsignedInteger('content_id')->default(0);
            $table->string('content', 1024)->default('');
            $table->unsignedInteger('like')->default(0);
            $table->unsignedInteger('dislike')->default(0);
            $table->unsignedInteger('grade')->default(0);
            $table->unsignedInteger('pid')->default(0)->comment('父ID。方便获取评论的层级关系');
            $table->unsignedInteger('rid')->default(0)->comment('根ID。方便获取某条评论下的所有评论');
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedInteger('reply_count')->default(0);
            $table->unsignedInteger('reply_user_id')->default(0);
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
        Schema::dropIfExists('comments');
    }
}
