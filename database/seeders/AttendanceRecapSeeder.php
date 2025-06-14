<?php

namespace Database\Seeders;

use App\Models\AttendanceRecap;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceRecapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AttendanceRecap::factory(15)->create();
    }
}
