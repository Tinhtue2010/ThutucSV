<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stop_studies', function (Blueprint $table) {
            $table->unsignedBigInteger('lop_id')->nullable();
            $table->foreign('lop_id')->references('id')->on('lops')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stop_studies', function (Blueprint $table) {
            //
        });
    }
};
