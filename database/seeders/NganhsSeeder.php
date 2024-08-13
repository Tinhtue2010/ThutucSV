<?php

namespace Database\Seeders;

use App\Models\Nganhs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class NganhsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = base_path('database/seeders/csv/nganhs.csv');
        if (!File::exists($filePath)) {
            $this->command->error("File CSV không tồn tại tại đường dẫn: " . $filePath);
            return;
        }

        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ',');

            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                Nganhs::create([
                    'manganh' => $row[0],
                    'tennganh' => $row[1],
                    'hedaotao' => $row[2],
                    'khoa_id' => !empty($row[3]) ? $row[3] : null,
                ]);
            }
            fclose($handle);
        } else {
            $this->command->error("Không thể mở file CSV.");
        }

        $this->command->info('Seeder cho bảng Nganhs đã được thực thi thành công.');
    }
}
