<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PayrollResource\Pages;
use App\Filament\Resources\PayrollResource\RelationManagers;
use App\Models\Payroll;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PayrollResource extends Resource
{
    protected static ?string $model = Payroll::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';

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
                    ->label('Employee'),
                Tables\Columns\TextColumn::make('period')
                    ->label('Period')
                    ->getStateUsing(fn($record) => "$record->period_start / $record->period_end"),
                Tables\Columns\TextColumn::make('total_salary')
                    ->money('idr')
                    ->label('Total Salary'),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('pdf')
                    ->label('Download Pdf')
                    ->icon('heroicon-o-document-text')
                    ->url(fn($record) => route('payroll.pdf', $record), true),
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
            'index' => Pages\ListPayrolls::route('/'),
            'create' => Pages\CreatePayroll::route('/create'),
            'edit' => Pages\EditPayroll::route('/{record}/edit'),
        ];
    }
}
