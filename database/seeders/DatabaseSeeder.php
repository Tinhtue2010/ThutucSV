<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            KhoasSeeder::class,
            NganhsSeeder::class,
            LopsSeeder::class,
            GiaoviensSeeder::class,
            SinhViensSeeder::class,
        ]);
    }
}
