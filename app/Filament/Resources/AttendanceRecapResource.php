<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceRecapResource\Pages;
use App\Filament\Resources\AttendanceRecapResource\RelationManagers;
use App\Models\AttendanceRecap;
use App\Services\PayrollService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AttendanceRecapResource extends Resource
{
    protected static ?string $model = AttendanceRecap::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

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
            ->modifyQueryUsing(function (Builder $query) {
                /**
                 * @var \App\Models\User
                 */
                $user = Auth::user();
                if ($user->hasRole('employee')) {
                    $query->where('employee_id', $user->employee_id);
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('employee.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('period')
                    ->label('Recap Period')
                    ->getStateUsing(fn($record) => "{$record->period_start} / {$record->period_end}")
                    ->icon('heroicon-o-calendar'),
                Tables\Columns\TextColumn::make('total_work_days')
                    ->badge()
                    ->icon('heroicon-o-calendar')
                    ->color('info')
                    ->suffix(' days'),
            ])
            ->recordUrl(null)
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->label('View')
                    ->url(fn($record) => route('filament.admin.resources.attendance-recaps.view', $record))
                    ->color('primary'),
                Tables\Actions\Action::make('payroll')
                    ->icon('heroicon-o-document-currency-dollar')
                    ->label('Create Payroll')
                    ->requiresConfirmation()
                    ->modalHeading("Confirm Generate Payroll")
                    ->color('warning')
                    ->authorize('create_attendance::recap')
                    ->action(function ($record, $livewire) {
                        $payroll = app(PayrollService::class)->generate($record);

                        if ($payroll) {
                            $livewire->redirect(route('filament.admin.resources.payrolls.index'));
                        }
                    })
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
            'index' => Pages\ListAttendanceRecaps::route('/'),
            'create' => Pages\CreateAttendanceRecap::route('/create'),
            'edit' => Pages\EditAttendanceRecap::route('/{record}/edit'),
            'view' => Pages\AttendanceRecapView::route('{record}/view')
        ];
    }
}
