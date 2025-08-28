<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $data['base_salary'] = str($data['base_salary'])->replace(['.', ','], '')->toString();

        return $data;
    }
}
