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
        $directoryPath = base_path('database/seeders/csv/hocsinh/');

        if (!File::exists($directoryPath)) {
            $this->command->error("Thư mục không tồn tại: " . $directoryPath);
            return;
        }

        $csvFiles = File::files($directoryPath);

        if (empty($csvFiles)) {
            $this->command->error("Không có file CSV nào trong thư mục: " . $directoryPath);
            return;
        }

        foreach ($csvFiles as $file) {
            $filePath = $file->getRealPath();

            $this->command->info("Đang xử lý file CSV: " . $filePath);

            $data = $this->importCSV(new \SplFileObject($filePath));
            $header = [
                "ma_hs" => "student_id",
                "ma_sinh_vien" => "student_code",
                "ho_ten" => "full_name",
                "ngay_sinh" => "date_of_birth",
                "so_dt_ca_nhan" => "phone",
                "email" => "email",
                "ten_lop" => "lop_id",
                "lien_khoa" => "school_year",
                "ngay_nhap_hoc" => "ngay_nhap_hoc",
                "ghi_chu_ho_so" => "note",
                "can_cuoc" => "cmnd",
                "ngay_cap_can_cuoc" => "date_range_cmnd",
            ];

            foreach ($data['data'] as $index => $item) {
                $student = new Student();
                foreach ($data['header'] as $index_header => $item_header) {
                    if (!isset($header[$data['header'][$index_header]])) {
                        continue;
                    }
                    $columnName = $header[$data['header'][$index_header]];
                    if ($data['header'][$index_header] == 'ten_lop') {
                        $lop = Lop::where('name', 'like', '%' . $item[$index_header] . '%')->first();
                        if ($lop) {
                            $student->lop_id = $lop->id;
                        } else {
                            throw new \Exception("Không tìm thấy lớp với tên: " . $item[$index_header]);
                        }
                    } else {
                        if ($columnName == "phone") {
                            $student->$columnName = '0' . $item[$index_header];
                        } else if ($columnName == "date_of_birth" || $columnName == "ngay_nhap_hoc" || $columnName == "date_range_cmnd") {
                            $student->$columnName = $this->convertDate('m/d/Y', $item[$index_header]);
                        } else if ($columnName == "gioitinh") {
                            if ($this->convertVietnamese($item[$index_header]) == 'nu') {
                                $student->$columnName = 0;
                            }
                            if ($this->convertVietnamese($item[$index_header]) == 'nam') {
                                $student->$columnName = 1;
                            }
                        } else if ($columnName == "email" && filter_var($item[$index_header], FILTER_VALIDATE_EMAIL)) {
                            $student->email = $item[$index_header];
                        } else {
                            $student->$columnName = $item[$index_header];
                        }
                    }
                }
                $student->save();

                $user = new User();

                $user->name = $student->full_name;
                $user->username = $student->student_code;
                $user->password = bcrypt($student->student_code);
                $user->student_id = $student->id;

                $user->save();
            }
        }

        $this->command->info('Seeder cho bảng sinhviens đã được thực thi thành công.');
    }
}
