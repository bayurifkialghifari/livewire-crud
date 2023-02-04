<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBreadField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bread_fields', function (Blueprint $table) {
            $table->dropColumn('order');
            $table->boolean('is_required')->change();
            $table->boolean('is_searchable')->change();
            $table->boolean('is_browse')->change();
            $table->boolean('is_readonly')->change();
            $table->boolean('is_edit')->change();
            $table->boolean('is_add')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
