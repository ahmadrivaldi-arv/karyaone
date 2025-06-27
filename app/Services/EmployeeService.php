<?php

namespace App\Services;

use App\Models\Employee;
use Filament\Notifications\Notification;

class EmployeeService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function addBenefit(Employee $employee, array $data)
    {

        $benefit = $employee->benefits()->create($data);

        Notification::make()
            ->success()
            ->title('Benefit Added')
            ->body('Benefit successfully added.')
            ->send();
    }
}
