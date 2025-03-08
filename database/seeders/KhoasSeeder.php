<?php

namespace Database\Seeders;

use App\Models\Khoa;
use App\Models\Nganhs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class KhoasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $response = Http::get('https://api.uhl.edu.vn/quanlysinhvien/api/APITichHop/DanhSachKhoa?Start=0&Length=10000&KeyWord'); // Thay URL API thật của bạn vào đây

        // Kiểm tra nếu API trả về dữ liệu thành công
        if ($response->successful()) {
            $data = $response->json(); // Chuyển đổi JSON thành mảng PHP

            // Lặp qua từng bản ghi và thêm vào database
            foreach ($data as $item) {
                Khoa::create([
                    'ten_khoa' => $item['ten_khoa'], // Thay bằng các trường dữ liệu phù hợp
                    'ma_khoa' => $item['ma_khoa'],
                ]);
            }
        } else {
            $this->command->error('Không thể lấy dữ liệu từ API.');
        }
        
        $filePath = base_path('database/seeders/csv/khoas.csv');
        if (!File::exists($filePath)) {
            $this->command->error("File CSV không tồn tại tại đường dẫn: " . $filePath);
            return;
        }

        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ',');

            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                Khoa::create([
                    'name' => $row[0],
                ]);
            }
            fclose($handle);
        } else {
            $this->command->error("Không thể mở file CSV.");
        }

        $this->command->info('Seeder cho bảng Khoas đã được thực thi thành công.');
    }
}
