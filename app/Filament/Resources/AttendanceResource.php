<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Application';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl('')
            ->columns([
                Tables\Columns\TextColumn::make('employee.name')
                    ->searchable(),
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
                Tables\Filters\SelectFilter::make('employee_id')
                    ->label('Employee')
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('attendance_from')
                            ->default(today())
                            ->native(false),
                        Forms\Components\DatePicker::make('attendance_until')
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
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
