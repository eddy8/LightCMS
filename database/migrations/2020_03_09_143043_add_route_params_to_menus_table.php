<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRouteParamsToMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->string('route_params', 91)->default('')->comment('路由参数');
            $table->dropUnique('menus_route_unique');
            $table->unique(['route', 'route_params']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('route_params');
            $table->dropUnique('menus_route_route_params_unique');
            $table->unique('route');
        });
    }
}
