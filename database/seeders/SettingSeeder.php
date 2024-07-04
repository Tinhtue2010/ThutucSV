<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = new Setting();
        $setting->name = "Thời gian nhận đơn xin miễn giảm học phí";
        $setting->slug = "rhs";
        $setting->data = json_encode(["batdau"=>"","ketthuc"=>""]);
        $setting->save();

        $setting = new Setting();
        $setting->name = "Thời gian nhận đơn xin trợ cấp xã hội";
        $setting->slug = "tcxh";
        $setting->data = json_encode(["batdau"=>"","ketthuc"=>""]);
        $setting->save();

        $setting = new Setting();
        $setting->name = "Thời gian nhận đơn xin chế độ chính sách";
        $setting->slug = "cdcs";
        $setting->data = json_encode(["batdau"=>"","ketthuc"=>""]);
        $setting->save();
    }
}
