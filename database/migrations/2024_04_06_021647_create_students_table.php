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
            $table->string('student_id')->nullable()->unique();
            $table->date('date_of_birth')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable()->unique();

            $table->unsignedBigInteger('lop_id')->nullable();
            $table->foreign('lop_id')->references('id')->on('lops')->onDelete('set null');

            $table->year('school_year')->nullable();
            $table->integer('sum_point')->default(0);
            $table->boolean('he_tuyen_sinh')->nullable();
            $table->string('nganh_tuyen_sinh')->nullable();
            $table->string('trinh_do')->nullable();
            $table->date('ngay_nhap_hoc')->nullable();
            $table->string('gv_tiep_nhan')->nullable();
            $table->string('gv_thu_tien')->nullable();
            $table->bigInteger('so_tien')->nullable();
            $table->text('note')->nullable();

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
