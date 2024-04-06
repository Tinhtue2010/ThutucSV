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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique()->nullable(); // có 2 loại đăng nhập 1 là dùng mã sinh viên, 2 là sử dụng username
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default(1); // 0 admin, 1 học sinh, 2 giáo viên, 3 lãnh đạo khoa, 4 phòng công tác hssv, 5 lãnh đạo ctsv, 6 lãnh đạo trường
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
