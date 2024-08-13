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
        Schema::table('lops', function (Blueprint $table) {
            $table->string('nganh_id')->nullable();
            $table->foreign('nganh_id')->references('manganh')->on('nganhs')->onDelete('set null');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lops', function (Blueprint $table) {
            //
        });
    }
};
