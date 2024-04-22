<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SQLSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = file_get_contents(database_path('seeders/sql/khoas.sql'));
        DB::unprepared($sql);


        $sql = file_get_contents(database_path('seeders/sql/teachers.sql'));
        DB::unprepared($sql);
        
        $sql = file_get_contents(database_path('seeders/sql/lops.sql'));
        DB::unprepared($sql);
        
        $sql = file_get_contents(database_path('seeders/sql/students.sql'));
        DB::unprepared($sql);


        $sql = file_get_contents(database_path('seeders/sql/users.sql'));
        DB::unprepared($sql);
    }
}
