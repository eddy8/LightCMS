<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensitiveWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensitive_words', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->default('');
            $table->string('noun')->default('');
            $table->string('verb')->default('');
            $table->string('exclusive')->default('');
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
        Schema::dropIfExists('sensitive_words');
    }
}
