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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();

            $table->string('full_name')->nullable();

            $table->string('ma_khoa')->nullable();
            $table->foreign('ma_khoa')->references('ma_khoa')->on('khoas')->onDelete('set null');

            $table->string('dia_chi')->nullable();

            $table->string('sdt')->nullable();
            $table->string('email')->nullable();

            $table->string('chuc_danh')->nullable();

            $table->string('chu_ky')->nullable();

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
        Schema::dropIfExists('teachers');
    }
};
