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
use Filament\Support\RawJs;
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
                                'male' => 'Male',
                                'female' => 'Female'
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
                        Forms\Components\TextInput::make('code')
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
                            ->options(function (?Employee $record, Get $get, Set $set) {

                                $departmentId = $get('department_id') ?? $record?->position?->department_id;

                                if (filled($record) && blank($get('department_id'))) {
                                    $set('position_id', $record->position_id);
                                    $set('department_id', $record->position->department_id);
                                }

                                if (!$departmentId) {
                                    return [];
                                }

                                return Position::where('department_id', $departmentId)
                                    ->pluck('name', 'id');
                            }),
                        Forms\Components\DatePicker::make('join_date')
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('base_salary')
                            ->mask(RawJs::make(<<<'JS'
                                            $money($input, '.', ',', 2)
                                            JS))
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive'
                            ])
                            ->required()
                            ->native(false)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(fn($record) => route('filament.admin.resources.employees.view', $record))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->sortable()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'male' => 'Male',
                        'female' => 'Female'
                    })
                    ->badge()
                    ->icon(fn($state) => match ($state) {
                        'male' => 'heroicon-o-user',
                        'female' => 'heroicon-o-user-minus'
                    })
                    ->color(fn($state) => match ($state) {
                        'male' => 'danger',
                        'female' => 'info'
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
            'view' => Pages\Employee\ViewEmployee::route('/{record}/view')
        ];
    }
}
