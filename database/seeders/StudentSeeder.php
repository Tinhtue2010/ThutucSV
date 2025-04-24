<?php

namespace Database\Seeders;

use App\Models\Khoa;
use App\Models\Lop;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Khoa::create([
            'name' => 'Công nghệ thông tin',
        ]);
        Lop::create([
            'name' => 'Khoa học máy tính',
            'khoa_id' => 1,
            'nganh' => 'Công nghệ thông tin'
        ]);
        Student::create([
            'full_name' => 'Nguyen Van A',
            'student_code' => 'SV001',
            'date_of_birth' => '1998-05-15',
            'phone' => '0123456789',
            'email' => 'nguyenvana@example.com',
            'lop_id' => 1,
            'nien_khoa' => 2022,
            'sum_point' => 85,
            'he_tuyen_sinh' => 1,
            'nganh_tuyen_sinh' => 'Công nghệ thông tin',
            'trinh_do' => 'Đại học',
            'ngay_nhap_hoc' => '2021-09-01',
            'gv_tiep_nhan' => 'Nguyen Thi B',
            'gv_thu_tien' => 'Tran Van C',
            'so_tien' => 5000000,
            'status_dk' => 1,
            'note' => 'Ghi chú cho sinh viên',
        ]);

        $user = new User();
        $user->name = 'Phạm Văn A';
        $user->password = bcrypt('SV001');
        $user->student_id = 1;
        $user->save();

        Student::create([
            'full_name' => 'Nguyen Van B',
            'student_code' => 'SV002',
            'date_of_birth' => '1998-05-15',
            'phone' => '0123456789',
            'email' => 'nguyenvanb@example.com',
            'lop_id' => 1,
            'nien_khoa' => 2022,
            'sum_point' => 85,
            'he_tuyen_sinh' => 1,
            'nganh_tuyen_sinh' => 'Công nghệ thông tin',
            'trinh_do' => 'Đại học',
            'ngay_nhap_hoc' => '2021-09-01',
            'gv_tiep_nhan' => 'Nguyen Thi B',
            'gv_thu_tien' => 'Tran Van C',
            'so_tien' => 5000000,
            'status_dk' => 1,
            'note' => 'Ghi chú cho sinh viên',
        ]);

        $user = new User();
        $user->name = 'Phạm Văn B';
        $user->password = bcrypt('SV002');
        $user->student_id = 1;
        $user->save();
    }
}
