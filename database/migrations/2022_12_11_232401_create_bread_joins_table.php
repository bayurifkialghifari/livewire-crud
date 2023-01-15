<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreadJoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bread_joins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bread_id')->nullable()->index();
            $table->string('origin_table')->nullable();
            $table->string('origin_key')->nullable();
            $table->string('foreign_table')->nullable();
            $table->string('foreign_key')->nullable();
            $table->string('join_type')->nullable();
            $table->foreign('bread_id')->references('id')->on('breads')->onDelete('cascade');
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
        Schema::dropIfExists('bread_joins');
    }
}
