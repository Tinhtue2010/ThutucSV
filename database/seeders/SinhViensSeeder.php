<?php

namespace Database\Seeders;

use App\Helpers\CommonHelper;
use App\Models\Khoa;
use App\Models\Lop;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class SinhViensSeeder extends Seeder
{
    use CommonHelper;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $start = 0;
        $length = 100;
        do {
            $response = Http::get("https://api.uhl.edu.vn/quanlysinhvien/api/APITichHop/DanhSachSinhVien", [
                'Start' => $start,
                'Length' => $length,
                'KeyWord' => ''
            ]);
        
            if ($response->successful()) {
                $data = $response->json();
        
                if (isset($data['result']['data']) && is_array($data['result']['data']) && count($data['result']['data']) > 0) {
                    foreach ($data['result']['data'] as $index => $item) {
                        try {
                            DB::beginTransaction(); // Bắt đầu transaction
        
                            $student = Student::create([
                                'full_name' => $item['hoTen'],
                                'student_code' => $item['maSV'] == '' ? null : $item['maSV'],
                                'date_of_birth' => $item['ngaySinh'],
                                'ma_lop' => Lop::where('name', 'LIKE', '%' . $item['tenLop'] . '%')->value('ma_lop'),
                                'nien_khoa' => $item['nienKhoa'],
                                'khoa_hoc' => $item['khoaHoc'],
                                'trinh_do' => $item['tenHe'],
                                'gioitinh' => $item['gioiTinh'] == "Nam" ? 1 : 0,
                            ]);
        
                            if ($item['maSV'] != '') {
                                User::create([
                                    'username' => $item['maSV'],
                                    'password' => bcrypt($item['maSV']),
                                    'name' => $item['hoTen'],
                                    'student_id' => $student->id,
                                    'role' => 1,
                                ]);
                            }
        
                            DB::commit(); // Lưu lại dữ liệu nếu không có lỗi
                        } catch (\Exception $e) {
                            DB::rollBack(); // Hoàn tác nếu có lỗi
                            $this->command->error("Lỗi khi thêm sinh viên: " . $item['hoTen'] . " - " . $e->getMessage());
                            continue; // Bỏ qua lỗi và tiếp tục vòng lặp
                        }
                    }
        
                    $this->command->info("Đã thêm " . count($data['result']['data']) . " sinh viên từ API (Start = $start).");
        
                    $start += $length;
                } else {
                    $this->command->info('Đã lấy hết dữ liệu.');
                    break;
                }
            } else {
                $this->command->error('Không thể lấy dữ liệu từ API.');
                break;
            }
        } while (true);
        
    }
}
