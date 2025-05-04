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
        $response = Http::withoutVerifying()->get('https://api.uhl.edu.vn/quanlysinhvien/api/APITichHop/DanhSachKhoa?Start=0&Length=10000&KeyWord');

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['result']['data']) && is_array($data['result']['data'])) {
                foreach ($data['result']['data'] as $item) {
                    Khoa::create([
                        'name' => $item['tenKhoa'],
                        'ma_khoa' => $item['maKhoa'],
                    ]);
                }

                $this->command->info('Seeder cho bảng Khoas đã được thực thi thành công.');
            } else {
                $this->command->error('Dữ liệu API không hợp lệ hoặc không có dữ liệu.');
            }
        } else {
            $this->command->error('Không thể lấy dữ liệu từ API.');
        }
    }
}
