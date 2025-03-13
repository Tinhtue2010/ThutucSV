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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('student_code')->nullable()->unique();
            
            $table->date('date_of_birth')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->string('ma_lop')->nullable();
            $table->foreign('ma_lop')->references('ma_lop')->on('lops')->onDelete('set null');

            $table->string('nien_khoa')->nullable();
            $table->integer('khoa_hoc')->nullable();
            $table->string('trinh_do')->nullable();
            $table->text('note')->nullable();

            $table->string('cmnd')->nullable();
            $table->date('date_range_cmnd')->nullable();

            $table->boolean('gioitinh')->default(0);
            $table->boolean('status')->default(0);

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
        Schema::dropIfExists('students');
    }
};
