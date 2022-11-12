<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleHasMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_has_menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id')->nullable()->index();
            $table->unsignedBigInteger('menu_id')->nullable()->index();
            $table->unsignedBigInteger('sub_menu_id')->nullable()->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('sub_menu_id')->references('id')->on('sub_menus')->onDelete('cascade');
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
        Schema::dropIfExists('role_has_menus');
    }
}
