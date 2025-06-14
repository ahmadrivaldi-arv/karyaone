<?php

namespace App\Filament\Resources\AttendanceRecapResource\Pages;

use App\Filament\Resources\AttendanceRecapResource;
use App\Models\Employee;
use App\Services\AttendanceRecapService;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;

class ListAttendanceRecaps extends ListRecords
{
    protected static string $resource = AttendanceRecapResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create')
                ->label('Create Recap')
                ->modal()
                ->icon('heroicon-o-folder-plus')
                ->form([
                    Forms\Components\Select::make('employee_id')
                        ->label('Employee')
                        ->options(Employee::pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                    Forms\Components\DatePicker::make('period_start')->label('From Period')
                        ->native(false)
                        ->required(),
                    Forms\Components\DatePicker::make('period_end')->label('To Period')
                        ->required()
                        ->native(false),
                ])->action(fn($data) => app(AttendanceRecapService::class)->generateRecap($data)),
        ];
    }
}
