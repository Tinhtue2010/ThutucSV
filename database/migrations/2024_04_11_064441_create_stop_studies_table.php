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
        Schema::create('stop_studies', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('set null');

            $table->integer('round')->default(0);

            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('stop_studies')->onDelete('set null');


            $table->string("note")->nullable();

            $table->boolean('status')->default(0);

            $table->string('ma_lop')->nullable();
            $table->foreign('ma_lop')->references('ma_lop')->on('lops')->onDelete('set null');

            $table->text('files')->nullable();
            
            $table->string('type')->default(0);

            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('set null');

            $table->boolean('is_update')->default(0);
            $table->boolean('is_pay')->default(0); // 0 chưa xác định, 1 thanh toán, 2 chưa thanh toán
            $table->string('note_pay')->default('');
            $table->dateTime('time_receive')->nullable();

            $table->boolean('type_miengiamhp')->nullable();

            $table->bigInteger('hocphi')->default(0);

            $table->integer('phantramgiam')->default(0);

            $table->json('tiepnhan')->nullable();
            $table->json('ykien')->nullable();
            $table->json('lanhdaophong')->nullable();
            $table->json('lanhdaotruong')->nullable();

            $table->bigInteger('muchotrohp')->default(1080000)->nullable();
            $table->bigInteger('muctrocapxh')->default(0)->nullable();

            $table->json('doi_tuong_chinh_sach')->nullable();

            $table->json("che_do_chinh_sach_data")->nullable();

            $table->string('diachi')->nullable();
            $table->integer('km')->nullable();

            $table->string('file_name')->nullable(); 

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
        Schema::dropIfExists('stop_studies');
    }
};
