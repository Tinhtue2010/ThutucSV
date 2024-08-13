<?php

namespace Database\Seeders;

use App\Helpers\CommonHelper;
use App\Models\Khoa;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GiaoviensSeeder extends Seeder
{
    use CommonHelper;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = base_path('database/seeders/csv/giaoviens.csv');
        if (!File::exists($filePath)) {
            $this->command->error("File CSV không tồn tại tại đường dẫn: " . $filePath);
            return;
        }

        $data = $this->importCSV(new \SplFileObject($filePath));
        $header = [
            "ho_ten" => "full_name",
            "khoa" => "khoa_id",
            "dia_chi" => "dia_chi",
            "dien_thoai" => "sdt",
            "email" => "email",
            "chuc_danh" => "chuc_danh",
            "tai_khoan" => "tai_khoan",
        ];
        foreach ($data['data'] as $index => $item) {
            $teacher = new Teacher();
            $user = new User();
            foreach ($data['header'] as $index_header => $item_header) {
                if (!isset($header[$data['header'][$index_header]])) {
                    continue;
                }
                $columnName = $header[$data['header'][$index_header]];
                if ($data['header'][$index_header] == 'khoa') {
                    if ($item[$index_header] == "" || $item[$index_header] == null) {
                    } else {
                        $khoa = Khoa::where('name', 'like', '%' . $item[$index_header] . '%')->first();
                        if ($khoa) {
                            $teacher->khoa_id = $khoa->id;
                        } else {
                            throw new \Exception("Không tìm thấy khoa với tên: " . $item[$index_header]);
                        }
                    }
                } elseif ($data['header'][$index_header] == 'tai_khoan') {
                    $quyen_index = array_search("quyen", $data['header']);
                    if ($quyen_index !== false) {
                        $user->role =  $item[$quyen_index];
                    }
                    $user->username = $item[$index_header];
                    $user->password = bcrypt($item[$index_header]);
                } else {
                    $teacher->$columnName = $item[$index_header];
                    if ($data['header'][$index_header] == 'email') {
                        if ($user->username == null) {
                            $quyen_index = array_search("quyen", $data['header']);
                            if ($quyen_index !== false) {
                                $user->role =  $item[$quyen_index];
                            }
                            $user->username = explode('@', $item[$index_header])[0];
                            $user->password = bcrypt(explode('@', $item[$index_header])[0]);
                        }
                    }
                }
            }
            $teacher->save();
            $user->name = $teacher->full_name;
            $user->teacher_id = $teacher->id;
            $user->save();
        }
        $this->command->info('Seeder cho bảng giaoviens đã được thực thi thành công.');
    }
}
