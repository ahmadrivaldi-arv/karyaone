<?php
namespace Database\Factories;

use App\Models\AttendanceRecap;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceRecapFactory extends Factory
{
    protected $model = AttendanceRecap::class;

    // Simpan kombinasi yang sudah dibuat supaya tidak duplikat
    protected static $existingRecaps = [];

    public function definition()
    {
        // Pastikan ada employee, kalau belum return null (atau buat dummy employee dulu)
        $employee = Employee::inRandomOrder()->first();

        if (!$employee) {
            return [];
        }

        $startDay = 27; // Tanggal mulai recap, bisa disesuaikan

        $now = now();

        // Cari periode unik, ulangi sampai ketemu yang belum ada
        do {
            // Pilih periode start random 0-6 bulan lalu
            $periodStart = $now->copy()->day($startDay)->subMonths($this->faker->numberBetween(0, 6));
            $periodEnd = $periodStart->copy()->addMonth()->subDay();

            $key = $employee->id . '-' . $periodStart->format('Y-m-d') . '-' . $periodEnd->format('Y-m-d');

        } while (
            in_array($key, self::$existingRecaps)
            || AttendanceRecap::where('employee_id', $employee->id)
                ->where('period_start', $periodStart->format('Y-m-d'))
                ->where('period_end', $periodEnd->format('Y-m-d'))
                ->exists()
        );

        // Simpan key supaya tidak dipakai lagi di batch ini
        self::$existingRecaps[] = $key;

        $totalWorkDays = 22;

        $presentDays = $this->faker->numberBetween(15, $totalWorkDays);
        $lateDays = $this->faker->numberBetween(0, $totalWorkDays - $presentDays);
        $sickDays = $this->faker->numberBetween(0, 5);
        $leaveDays = $this->faker->numberBetween(0, 3);
        $absentDays = $totalWorkDays - ($presentDays + $lateDays + $sickDays + $leaveDays);
        if ($absentDays < 0)
            $absentDays = 0;

        return [
            'employee_id' => $employee->id,
            'period_start' => $periodStart->format('Y-m-d'),
            'period_end' => $periodEnd->format('Y-m-d'),
            'present_days' => $presentDays,
            'late_days' => $lateDays,
            'sick_days' => $sickDays,
            'leave_days' => $leaveDays,
            'absent_days' => $absentDays,
            'total_work_days' => $totalWorkDays,
        ];
    }
}
