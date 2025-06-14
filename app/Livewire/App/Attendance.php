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


            if (now()->format('H:i') < '00:30') {
                Notification::make()
                    ->info()
                    ->title('Info')
                    ->body('Checkout is only allowed after 17:30.')
                    ->send();
            } else {
                Notification::make()
                    ->warning()
                    ->title('Warning')
                    ->body("You have already checked in today at {$this->attendance->check_in_time}.")
                    ->send();
            }

            return;
        }

        $checkInTime = now();
        $officeStart = now()->setTime(22, 0, 0);
        $lateInMin = 0;

        if ($checkInTime->greaterThan($officeStart)) {
            $lateInMin = $checkInTime->diffInMinutes($officeStart);
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
            ->body("You have successfully checked in at {$this->attendance->check_in}.")
            ->send();

        session()->flash('success', "Welcome <strong>{$employee->name}</strong>! Wishing you a productive and successful workday ahead.");

    }
}
