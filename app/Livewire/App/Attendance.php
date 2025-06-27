<?php

namespace App\Livewire\App;

use App\Models\Employee;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Livewire\Component;

class Attendance extends Component
{
    /**
     * @var bool
     */
    public bool $scanned = false;

    /**
     * @var int|null|null
     */
    public ?int $rfid = null;

    /**
     * @var \App\Models\Attendance|null|
     */
    public ?\App\Models\Attendance $attendance = null;

    public ?Collection $histories = null;

    public $notification = null;

    public function render()
    {
        $this->histories = \App\Models\Attendance::today()->limit(5)->get();

        return view('livewire.app.attendance')->title('Attendance');
    }

    public function scan($rfid = null)
    {
        $this->scanned = true;

        $employee = Employee::where('code', $rfid)->first();

        if (!$employee) {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Employee ID not found.')
                ->send();
            return;
        }

        if ($this->attendance = $employee->attendances()->today()->first()) {

            // If already checked out, show message
            if ($this->attendance->check_out_time) {
                Notification::make()
                    ->warning()
                    ->title('Already Checked Out')
                    ->body('You have already checked out today at ' . $this->attendance->check_out_time . '.')
                    ->send();
                return;
            }

            // Allow checkout at 17:30 or later
            if (now()->format('H:i') >= '17:30') {
                // Perform checkout logic here
                $this->attendance->update([
                    'check_out_time' => now(),
                ]);
                Notification::make()
                    ->success()
                    ->title('Success')
                    ->body("You have successfully checked out at " . now()->format('H:i') . ".")
                    ->send();
            } else {
                Notification::make()
                    ->warning()
                    ->title('Warning')
                    ->body('Checkout is only allowed at or after 17:30.')
                    ->send();
            }

            return;
        }

        $checkInTime = now();
        $officeStart = now()->setTime(9, 0, 0);
        $lateInMin = 0;

        if ($checkInTime->greaterThan($officeStart)) {
            $lateInMin = $checkInTime->diffInMinutes($officeStart);
        }

        // Prevent check-in before 06:30
        if ($checkInTime->format('H:i') < '06:30') {
            Notification::make()
                ->warning()
                ->title('Too Early')
                ->body('Check-in is not allowed before 06:30.')
                ->send();
            return;
        }

        $this->attendance = $employee->attendances()->create([
            'attendance_date' => $checkInTime,
            'check_in_time' => $checkInTime,
            'status' => 'present',
            'timing_status' => $checkInTime->format('H:i') > '09:15' ? 'late' : ($checkInTime->format('H:i') >= '09:00' ? 'on_time' : 'early'),
            'late_in_min' => $lateInMin,
        ]);

        Notification::make()
            ->success()
            ->title('Warning')
            ->body("You have successfully checked in at {$this->attendance->check_in_time}.")
            ->send();

        session()->flash('success', "Welcome <strong>{$employee->name}</strong>! Wishing you a productive and successful workday ahead.");

    }
}
