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

            $table->unsignedBigInteger('phieu_id')->nullable();
            $table->foreign('phieu_id')->references('id')->on('phieus')->onDelete('set null');

            $table->string("note")->nullable();

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
