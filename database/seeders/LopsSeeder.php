<?php

namespace Database\Seeders;

use App\Helpers\CommonHelper;
use App\Models\Khoa;
use App\Models\Lop;
use App\Models\Nganhs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

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
        $start = 0;
        $length = 100;
        do {
            $response = Http::withoutVerifying()->get("https://api.uhl.edu.vn/quanlysinhvien/api/APITichHop/DanhSachLop", [
                'Start' => $start,
                'Length' => $length,
                'KeyWord' => ''
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['result']['data']) && is_array($data['result']['data']) && count($data['result']['data']) > 0) {
                    foreach ($data['result']['data'] as $index => $item) {
                        $maLop = !empty($item['maLop']) ? $item['maLop'] : 'TEMP_' . $index;

                        $categories = [
                            "DL" => ["AU", "DVAU", "DVDL", "LH", "khách sạn", "ướng dẫ", "KS", "QK"],
                            "VH" => ["VH", "văn hóa"],
                            "NT" => ["hanh nhạc", "ội họa", "hạc cụ", "TNP", "TNT", "Múa", "ội Hoạ", "TTN", "THH"],
                            "NN" => ["TQ", "TA", "NN", "Tiếng Anh", "DL", "NB", "NA", "HQ", "Anh"],
                            "TS" => ["thủy sản", "TS"],
                            "CNTT" => ["KHMT", "CT", "tin học ƯD", "máy tính", "Tin", "KM"],
                            "SP" => ["MN", "CM", "SP", "STH", "SMN", "VBC"],
                            "MT" => ["TN&MT", "MT"]
                        ];
                        
                        $ma_khoa = null; 
                        
                        foreach ($categories as $key => $values) {
                            foreach ($values as $value) {
                                if (strpos($item['tenLop'], $value) !== false) {
                                    $ma_khoa = $key;
                                    break 2; 
                                }
                            }
                        }
                        
                        Lop::create([
                            'ma_lop' => $maLop,
                            'name' => $item['tenLop'],
                            'ma_khoa' => $ma_khoa,
                            'nganh_id' => $this->getMaNganh($item['tenLop']),
                        ]);
                        
                    }

                    $this->command->info("Đã thêm " . count($data['result']['data']) . " lớp từ API (Start = $start).");

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
