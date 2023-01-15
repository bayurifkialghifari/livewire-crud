<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('breads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('url_slug')->nullable();
            $table->string('display_name_singular')->nullable();
            $table->string('display_name_plural')->nullable();
            $table->string('icon')->nullable();
            $table->string('table_name')->nullable();
            $table->string('primary_key')->nullable();
            $table->string('order_by')->nullable();
            $table->string('order')->nullable();
            $table->tinyInteger('is_join')->nullable();
            $table->string('custom_button')->nullable();
            $table->string('controller')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('breads');
    }
}
