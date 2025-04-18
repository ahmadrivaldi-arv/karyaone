<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Filament\Forms;
use Livewire\Component as Livewire;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        $departments = Department::pluck('name', 'id')->toArray();

        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->columns(4)
                    ->schema([
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\Select::make('gender')
                            ->options([
                                'L' => 'Laki-Laki',
                                'P' => 'Perempuan'
                            ])->required()
                            ->native(false),
                        Forms\Components\TextInput::make('phone')->required(),
                        Forms\Components\TextInput::make('email')->required(),
                        Forms\Components\Textarea::make('address')
                            ->required()
                            ->columnSpanFull()
                    ]),
                Forms\Components\Section::make('Advanced Information')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('employee_code')
                            ->required()
                            ->label('RFID')
                            ->nullable(),
                        Forms\Components\Select::make('department_id')
                            ->label('Department')
                            ->options($departments)
                            ->native(false)
                            ->required()
                            ->live()
                            ->dehydrated(false)
                            ->afterStateUpdated(function (Set $set) {
                                $set('position_id', null);
                            }),
                        Forms\Components\Select::make('position_id')
                            ->label('Position')
                            ->required()
                            ->native(false)
                            ->disabled(fn(Get $get) => $get('department_id') === null)
                            ->options(function (Get $get) {
                                $departmentId = $get('department_id');

                                if (!$departmentId) {
                                    return [];
                                }

                                return Position::where('department_id', $departmentId)
                                    ->pluck('name', 'id');
                            }),
                        Forms\Components\DatePicker::make('join_date')
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('Status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive'
                            ])->required()->native(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('gender')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'L' => 'Male',
                        'P' => 'Female'
                    })
                    ->badge()
                    ->icon(fn($state) => match ($state) {
                        'L' => 'heroicon-o-user',
                        'P' => 'heroicon-o-user-minus'
                    })
                    ->color(fn($state) => match ($state) {
                        'L' => 'danger',
                        'P' => 'info'
                    }),
                Tables\Columns\TextColumn::make('department')
                    ->getStateUsing(fn($record) => "{$record->position->department->name} / {$record->position->name}"),
                Tables\Columns\TextColumn::make('contact')
                    ->getStateUsing(fn($record) => "{$record->email} / {$record->phone}"),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
