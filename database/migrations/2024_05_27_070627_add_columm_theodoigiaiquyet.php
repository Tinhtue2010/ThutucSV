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
            $table->json('tiepnhan')->nullable();
            $table->json('ykien')->nullable();
            $table->json('lanhdaophong')->nullable();
            $table->json('lanhdaotruong')->nullable();
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
