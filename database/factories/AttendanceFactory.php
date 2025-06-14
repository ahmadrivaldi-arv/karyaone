<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {

        $status = $this->faker->randomElement(['present', 'absent', 'leave', 'sick']);
        $checkIn = null;
        $checkOut = null;
        $timingStatus = null;
        $lateMinutes = null;

        if ($status === 'present') {
            $checkInTime = $this->faker->dateTimeBetween('08:00:00', '10:00:00');
            $checkIn = $checkInTime->format('H:i:s');

            $checkOutTime = $this->faker->dateTimeBetween('17:00:00', '19:00:00');
            $checkOut = $checkOutTime->format('H:i:s');

            // Hitung keterlambatan dari jam kerja ideal: 09:00:00
            $idealStart = \DateTime::createFromFormat('H:i:s', '09:00:00');
            $lateInMin = max(0, ($checkInTime->getTimestamp() - $idealStart->getTimestamp()) / 60);

            if ($lateInMin > 0) {
                $timingStatus = 'late';
                $lateMinutes = round($lateInMin);
            } elseif ($checkInTime < $idealStart) {
                $timingStatus = 'early';
                $lateMinutes = 0;
            } else {
                $timingStatus = 'on_time';
                $lateMinutes = 0;
            }
        }


        return [
            'check_in_time' => $checkIn,
            'check_out_time' => $checkOut,
            'note' => $this->faker->optional()->sentence(),
            'status' => $status,
            'timing_status' => $timingStatus,
            'late_in_min' => $lateMinutes,
        ];
    }
}
