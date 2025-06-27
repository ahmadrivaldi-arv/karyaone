<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceRecap;
use App\Models\Employee;
use Exception;
use Filament\Notifications\Notification;

class AttendanceRecapService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function generateRecap(array $data = [])
    {
        if (!isset($data['period_start'], $data['period_end'], $data['employee_id'])) {
            throw new Exception('Missing required keys: period_start, period_end, or employee_id.');
        }

        $existing = AttendanceRecap::where('employee_id', $data['employee_id'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('period_start', [$data['period_start'], $data['period_end']])
                    ->orWhereBetween('period_end', [$data['period_start'], $data['period_end']])
                    ->orWhere(function ($q) use ($data) {
                        $q->where('period_start', '<=', $data['period_start'])
                            ->where('period_end', '>=', $data['period_end']);
                    });
            })
            ->first();

        if ($existing) {
            Notification::make()
                ->title('Recap Already Exists')
                ->warning()
                ->body('Cannot create recap, recap with selected period already exists.')
                ->send();

            return;
        }

        $employee = Employee::find($data['employee_id']);

        $attendances = $employee->attendances()->period($data['period_start'], $data['period_end'])->get();

        // Calculate total work days in the period
        $periodStart = \Carbon\Carbon::parse($data['period_start']);
        $periodEnd = \Carbon\Carbon::parse($data['period_end']);
        $totalWorkDays = 0;

        for ($date = $periodStart->copy(); $date->lte($periodEnd); $date->addDay()) {
            // Assuming work days are Monday to Friday
            if ($date->isWeekday()) {
                $totalWorkDays++;
            }
        }

        $present = $attendances->where('status', 'present')->count();
        $late = $attendances->where('timing_status', 'late')->count();
        $leave = $attendances->where('status', 'leave')->count();
        $sick = $attendances->where('status', 'sick')->count();

        $absent = $totalWorkDays - $present - $leave - $sick;

        // Insert to recap
        AttendanceRecap::create([
            'employee_id' => $data['employee_id'],
            'period_start' => $data['period_start'],
            'period_end' => $data['period_end'],
            'total_work_days' => $totalWorkDays,
            'present_days' => $present,
            'absent_days' => $absent,
            'late_days' => $late,
            'leave_days' => $leave,
            'sick_days' => $sick,
        ]);
    }
}
