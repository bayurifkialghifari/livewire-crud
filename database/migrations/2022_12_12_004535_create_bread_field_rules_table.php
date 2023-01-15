<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreadFieldRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bread_field_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bread_field_id')->nullable()->index();
            $table->text('rule')->nullable();
            $table->text('message')->nullable();
            $table->foreign('bread_field_id')->references('id')->on('bread_fields')->onDelete('cascade');
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
        Schema::dropIfExists('bread_field_rules');
    }
}
