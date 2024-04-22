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
            $table->boolean('is_update')->default(0);
            $table->boolean('is_pay')->default(0); // 0 chưa xác định, 1 thanh toán, 2 chưa thanh toán
            $table->string('note_pay')->default('');
            $table->dateTime('time_receive')->nullable();
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
