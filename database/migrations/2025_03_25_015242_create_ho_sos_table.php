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
        Schema::create('ho_sos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_name')->nullable();
            $table->string('file_list')->nullable();
            $table->string('file_quyet_dinh')->nullable();

            $table->unsignedBigInteger('stop_studie_id')->nullable();
            $table->foreign('stop_studie_id')->references('id')->on('stop_studies')->onDelete('set null');

            $table->json('list_info')->nullable();

            $table->boolean('ky_hoc')->default(1);
            $table->string('nam_hoc')->nullable();
            $table->boolean('type')->default(2);

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
        Schema::dropIfExists('ho_sos');
    }
};
