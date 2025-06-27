<?php

namespace App\Services;

use App\Models\AttendanceRecap;
use App\Models\Employee;
use App\Models\EmployeeBenefit;
use App\Models\Payroll;
use App\Models\PayrollItem;
use Filament\Notifications\Notification;

class PayrollService
{

    public function generate(AttendanceRecap $recap): ?Payroll
    {
        if (Payroll::where('employee_id', $recap->employee_id)
            ->where('period_start', $recap->period_start)
            ->where('period_end', $recap->period_end)
            ->exists()
        ) {
            return null;
        }

        $employee = Employee::findOrFail($recap->employee_id);
        $baseSalary = $employee->base_salary;
        $totalAllowance = 0;
        $totalDeduction = 0;
        $items = [];

        $totalAbsent = $recap->absent_days ?? 0;
        $totalSick   = $recap->sick_days ?? 0;
        $totalLeave  = $recap->leave_days ?? 0;

        $deductionFromAbsence = $totalAbsent * 50000;
        $totalDeduction += $deductionFromAbsence;

        $items[] = [
            'name'         => "Gaji Pokok",
            'type'         => "allowance",
            'amount'       => $baseSalary,
            'amount_type'  => 'fixed',
            'is_taxable'   => false,
            'description'  => null,
            'created_at'   => now(),
            'updated_at'   => now(),
        ];

        $items[] = [
            'name'         => 'Potongan Keterlambatan',
            'type'         => 'deduction',
            'amount'       => $deductionFromAbsence,
            'amount_type'  => 'fixed',
            'is_taxable'   => false,
            'description'  => 'Automatic deduction for total absences',
            'created_at'   => now(),
            'updated_at'   => now(),
        ];


        foreach ($recap->employee->benefits as $benefit) {
            $amount = $benefit->amount_type === 'percentage'
                ? round($baseSalary * ($benefit->amount / 100))
                : round($benefit->amount);

            $items[] = [
                'name'         => $benefit->name,
                'type'         => $benefit->type,
                'amount'       => $amount,
                'amount_type'  => $benefit->amount_type,
                'is_taxable'   => $benefit->is_taxable,
                'description'  => $benefit->description,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];

            if ($benefit->type === 'allowance') $totalAllowance += $amount;
            else $totalDeduction += $amount;
        }

        $totalSalary = $baseSalary + $totalAllowance - $totalDeduction;

        $payroll = Payroll::create([
            'employee_id'     => $employee->id,
            'period_start'    => $recap->period_start,
            'period_end'      => $recap->period_end,
            'base_salary'     => $baseSalary,
            'total_allowance' => $totalAllowance,
            'total_deduction' => $totalDeduction,
            'total_salary'    => $totalSalary,
            'status'          => 'pending',
            'total_absent'    => $totalAbsent,
            'total_sick'      => $totalSick,
            'total_leave'     => $totalLeave,
        ]);


        foreach ($items as &$item) {
            $item['payroll_id'] = $payroll->id;
        }

        PayrollItem::insert($items);

        Notification::make()
            ->success()
            ->body("Successfully generated.")
            ->send();

        return $payroll;
    }
}
