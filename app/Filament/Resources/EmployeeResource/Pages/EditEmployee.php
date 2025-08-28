<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['base_salary'] = str($data['base_salary'])->numbers()->toString();

        return $data;
    }
}
