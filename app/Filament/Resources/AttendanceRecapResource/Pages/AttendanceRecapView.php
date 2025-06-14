<?php

namespace App\Filament\Resources\AttendanceRecapResource\Pages;

use App\Filament\Resources\AttendanceRecapResource;
use App\Filament\Resources\AttendanceRecapResource\Widgets\AttendanceRecapWidget;
use App\Models\Attendance;
use App\Models\AttendanceRecap;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Infolists;
use Filament\Resources\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class AttendanceRecapView extends Page implements HasForms, HasInfolists, HasTable
{
    use InteractsWithInfolists;
    use InteractsWithForms;
    use InteractsWithRecord;
    use InteractsWithTable;


    protected static string $resource = AttendanceRecapResource::class;

    protected static string $view = 'filament.resources.attendance-recap-resource.pages.attendance-recap-view';

    public function mount($record)
    {
        $this->record = $this->resolveRecord($record);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                Infolists\Components\Section::make('Employee Information')
                    ->columns(3)
                    ->schema([
                        Infolists\Components\TextEntry::make('employee.name'),
                        Infolists\Components\TextEntry::make('employee.position.name')
                            ->label('Department / Position')
                            ->getStateUsing(fn($record) => $record->employee->formatted_position),
                        Infolists\Components\TextEntry::make('recap_period')
                            ->label('Recap Period')
                            ->icon('heroicon-o-calendar-days')
                            ->getStateUsing(fn($record) => "$record->period_start / $record->period_end"),
                    ]),
                Infolists\Components\Section::make('Recap Information')
                    ->columns(3)
                    ->schema([
                        Infolists\Components\TextEntry::make('total_work_days')
                            ->label('Total Work Days')
                            ->icon('heroicon-o-calendar-days')
                            ->suffix(' Days')
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('present_days')
                            ->label('Total Present')
                            ->icon('heroicon-o-calendar-days')
                            ->suffix(' Days')
                            ->badge()
                            ->color('success'),
                        Infolists\Components\TextEntry::make('absent_days')
                            ->label('Total Absent')
                            ->icon('heroicon-o-x-mark')
                            ->suffix(' Days')
                            ->badge()
                            ->color('danger'),
                        Infolists\Components\TextEntry::make('sick_days')
                            ->label('Total Sick')
                            ->icon('heroicon-o-shield-exclamation')
                            ->suffix(' Days')
                            ->badge()
                            ->color('warning'),
                        Infolists\Components\TextEntry::make('leave_days')
                            ->label('Total Leave')
                            ->icon('heroicon-o-calendar-date-range')
                            ->suffix(' Days')
                            ->badge()
                            ->color('gray'),
                    ])

            ]);
    }

    public function table(Table $table): Table
    {
        $attendancesQuery = Attendance::query()->whereBetween('attendance_date', [$this->record->period_start, $this->record->period_end])
            ->where('employee_id', $this->record->employee_id);

        return $table->query($attendancesQuery)
            ->columns([
                Tables\Columns\TextColumn::make('attendance_date'),
                Tables\Columns\TextColumn::make('Check In')
                    ->label('Check In / Check Out')
                    ->getStateUsing(fn($record) => ($record->check_in_time ?? '-:-') . ' / ' . ($record->check_out_time ?: '-:-')),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn($record) => match ($record->status) {
                        'present' => 'success',
                        'absent' => 'danger',
                        'sick' => 'warning',
                        'leave' => 'gray',
                    })->formatStateUsing(fn($record) => str($record->status)->title()),
                Tables\Columns\TextColumn::make('timing_status')->badge()
                    ->color(fn($record) => match ($record->timing_status) {
                        'on_time' => 'success',
                        'late' => 'danger',
                        'early' => 'warning',
                    })->formatStateUsing(fn($record) => match ($record->timing_status) {
                        'on_time' => 'On Time',
                        'late' => 'Late',
                        'early' => 'Early',
                    })->prefix(fn($record) => $record->isLate() ? "$record->late_in_human " : ""),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'present' => 'present',
                        'absent' => 'absent',
                        'sick' => 'sick',
                        'leave' => 'leave'
                    ]),
                Tables\Filters\Filter::make('date')
                    ->form([
                        DatePicker::make('attendance_from')
                            ->native(false),
                        DatePicker::make('attendance_until')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if ($data['attendance_from'] && !$data['attendance_until']) {
                            // Only 'attendance_from' filled, filter only that day
                            return $query->whereDate('attendance_date', $data['attendance_from']);
                        } elseif ($data['attendance_from'] && $data['attendance_until']) {
                            // Both filled, filter between the two dates (inclusive)
                            return $query->whereBetween('attendance_date', [
                                $data['attendance_from'],
                                $data['attendance_until'],
                            ]);
                        } elseif (!$data['attendance_from'] && $data['attendance_until']) {
                            // Only 'attendance_until' filled, filter only that day
                            return $query->whereDate('attendance_date', $data['attendance_until']);
                        }

                        return $query;
                    })
            ])
            ->emptyStateIcon('heroicon-o-inbox-stack');
    }

    public function getHeaderWidgets(): array
    {
        return [
            // AttendanceRecapWidget::class
        ];
    }
}
