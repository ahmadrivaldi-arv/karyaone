<?php

namespace App\Filament\Resources\AttendanceRecapResource\Widgets;

use App\Models\AttendanceRecap;
use Filament\Actions\Concerns\InteractsWithRecord;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AttendanceRecapWidget extends BaseWidget
{
    use InteractsWithRecord;

    protected static ?string $pollingInterval = null;

    public function mount(AttendanceRecap $record)
    {
        $this->record = $record;
    }

    public function getColumns(): int
    {
        return 2;
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Present', $this->record->present_days)
                ->icon('heroicon-o-check-badge')
                ->description(description: "Total present days")
                ->color('success'),
            Stat::make('Absent', $this->record->absent_days)
                ->icon('heroicon-o-x-mark')
                ->description(description: "Total absent days")
                ->color('danger'),
            Stat::make('Late', $this->record->late_days)
                ->icon('heroicon-o-clock')
                ->description(description: "Total late days")
                ->color('danger'),
            Stat::make('Sick', $this->record->late_days)
                ->icon('heroicon-o-shield-exclamation')
                ->description(description: "Total sick days")
                ->color('warning'),
            Stat::make('Leave', $this->record->late_days)
                ->icon('heroicon-o-calendar-date-range')
                ->description(description: "Total leave days")
                ->color('dark'),
        ];
    }
}
