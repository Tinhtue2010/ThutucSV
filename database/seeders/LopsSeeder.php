<?php

namespace Database\Seeders;

use App\Helpers\CommonHelper;
use App\Models\Khoa;
use App\Models\Lop;
use App\Models\Nganhs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class LopsSeeder extends Seeder
{
    use CommonHelper;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = base_path('database/seeders/csv/lops.csv');
        if (!File::exists($filePath)) {
            $this->command->error("File CSV không tồn tại tại đường dẫn: " . $filePath);
            return;
        }

        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ',');

            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $lop = new Lop();

                $khoa = Khoa::where('name', 'like', '%' . $row[1] . '%')->first();
                if ($khoa) {
                    $lop->khoa_id = $khoa->id;
                } else {
                    throw new \Exception("Không tìm thấy khoa với tên: " . $row[1]);
                }

                $lop->name = $row[0];
                $lop->nganh_id = $this->getMaNganh($row[0]);
                
                $lop->save();
            }
            fclose($handle);
        } else {
            $this->command->error("Không thể mở file CSV.");
        }

        $this->command->info('Seeder cho bảng Khoas đã được thực thi thành công.');
    }
}
