<?php

namespace App\Filament\Resources\AttendanceRecapResource\Pages;

use App\Filament\Resources\AttendanceRecapResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttendanceRecap extends EditRecord
{
    protected static string $resource = AttendanceRecapResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
