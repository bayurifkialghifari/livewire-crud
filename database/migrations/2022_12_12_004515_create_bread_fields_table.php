<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreadFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bread_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bread_id')->nullable()->index();
            $table->string('foreign_table')->nullable();
            $table->string('foreign_key')->nullable();
            $table->string('foreign_field')->nullable();
            $table->string('field')->nullable();
            $table->string('type')->nullable();
            $table->string('display_name')->nullable();
            $table->string('placeholder')->nullable();
            $table->string('class_alt')->nullable();
            $table->string('default_value')->nullable();
            $table->string('id_alt')->nullable();
            $table->string('is_required')->nullable();
            $table->string('is_searchable')->nullable();
            $table->string('is_browse')->nullable();
            $table->string('is_readonly')->nullable();
            $table->string('is_edit')->nullable();
            $table->string('is_add')->nullable();
            $table->string('source')->nullable();
            $table->string('source_id')->nullable();
            $table->string('source_value')->nullable();
            $table->string('file_accept')->nullable();
            $table->text('description')->nullable();
            $table->string('description_class')->nullable();
            $table->string('order')->nullable();
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
        Schema::dropIfExists('bread_fields');
    }
}
