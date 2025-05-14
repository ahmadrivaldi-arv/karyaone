<?php

namespace App\Livewire\App;

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

    public array $histories = [
        [
            'name' => 'Ahmad Rivaldi',
            'department' => 'IT',
            'position' => 'Web Developer',
            'attendance_time' => '2025-05-06 9:05:10',
            'status' => 'on_time'
        ],
        [
            'name' => 'Roni Abdul Hamid',
            'department' => 'IT',
            'position' => 'Design & Web Development',
            'attendance_time' => '2025-05-06 9:15:32',
            'status' => 'late'
        ],
        [
            'name' => 'Faqy Iskandar',
            'department' => 'Finance & Accounting',
            'position' => 'Tax & Compliance Officer',
            'attendance_time' => '2025-05-06 8:38:50',
            'status' => 'early'
        ],
    ];

    public function render()
    {
        return view('livewire.app.attendance')->title('Attendance');
    }

    public function scan($rfid = null)
    {
        $this->scanned = true;
        $this->rfid = $rfid;

        $status = fake()->randomElement(['early', 'on_time', 'late']);
        $statusLabel = null;

        if ($status === 'late') {
            $rand = rand(5, 30);

            $statusLabel = "$rand mins late";
        }

        if ($status === 'early') {
            $rand = rand(5, 30);

            $statusLabel = "$rand mins early";
        }

        array_unshift($this->histories, [
            'name' => fake()->name,
            'department' => fake()->jobTitle(),
            'position' => fake()->jobTitle(),
            'attendance_time' => now(),
            'status' => $status,
            'status_label' => $statusLabel
        ]);
    }
}
