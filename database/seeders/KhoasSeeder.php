<?php

namespace Database\Seeders;

use App\Models\Khoa;
use App\Models\Nganhs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class KhoasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
