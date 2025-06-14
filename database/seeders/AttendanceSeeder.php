<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $employee = Employee::firstWhere(['email' => 'rivaldi19122@gmail.com']);

        for ($i = 0; $i < 30; $i++) {
            $date = now()->subDays($i)->format('Y-m-d');

            // Cek jika sudah ada (untuk hindari unique constraint)
            if (Attendance::where('employee_id', $employee->id)->where('attendance_date', $date)->exists()) {
                continue;
            }

            Attendance::factory()
                ->create([
                    'attendance_date' => $date,
                    'employee_id' => $employee->id,
                ]);
        }
    }
}
