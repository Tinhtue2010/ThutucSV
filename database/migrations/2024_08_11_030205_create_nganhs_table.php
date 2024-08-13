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
        Schema::create('nganhs', function (Blueprint $table) {
            $table->string('manganh')->unique()->primary();
            $table->string('tennganh');
            $table->boolean('hedaotao')->default(0);
            
            $table->unsignedBigInteger('khoa_id')->nullable();
            $table->foreign('khoa_id')->references('id')->on('khoas')->onDelete('set null');
            
            
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
        Schema::dropIfExists('nganhs');
    }
};
