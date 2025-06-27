<?php

namespace App\Filament\Resources\EmployeeResource\Pages\Employee;

use App\Filament\Resources\EmployeeResource;
use App\Models\Employee;
use App\Models\EmployeeBenefit;
use App\Services\EmployeeService;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Support\RawJs;

class ViewEmployee extends Page implements HasForms, HasInfolists, HasTable, HasActions
{
    use InteractsWithActions;
    // use InteractsWithRecord;
    use InteractsWithInfolists;
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = EmployeeResource::class;

    protected static string $view = 'filament.resources.employee-resource.pages.employee.view-employee';

    public $record;

    public function mount($record): void
    {
        $this->record = Employee::find($record);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->record($this->record)->schema([
            Infolists\Components\Section::make('Personal Information')
                ->columns([
                    'lg' => 3,
                    'xl' => 4
                ])
                ->schema([
                    Infolists\Components\TextEntry::make('name'),
                    Infolists\Components\TextEntry::make('contact')
                        ->getStateUsing(fn($record) => "$record->email / $record->phone"),
                    Infolists\Components\TextEntry::make('gender')
                        ->badge()
                        ->icon(fn($record) => match ($record->gender) {
                            'male' => 'heroicon-o-user-plus',
                            'female' => 'heroicon-o-user-minus',
                        })->color(fn($record) => match ($record->gender) {
                            'male' => 'danger',
                            'female' => 'info',
                        }),
                    Infolists\Components\TextEntry::make('department_position')
                        ->label('Department / Position')
                        ->getStateUsing(fn($record) => $record->formatted_position),
                    Infolists\Components\TextEntry::make('address')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public function table(Table $table): Table
    {

        return $table->query($this->record->benefits()->getQuery())
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'allowance' => 'success',
                        'deduction' => 'danger',
                    })
                    ->icon(fn($state) => match ($state) {
                        'allowance' => 'heroicon-o-plus',
                        'deduction' => 'heroicon-o-minus',
                    }),
                Tables\Columns\TextColumn::make('amount_type')
                    ->label('Amount Type')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'fixed' => 'info',
                        'percentage' => 'warning',
                    })
                    ->icon(fn($state) => match ($state) {
                        'fixed' => 'heroicon-o-check-circle',
                        'percentage' => 'heroicon-o-percent-badge',
                    }),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->amount_type === 'percentage') {
                            return $state . ' %';
                        }

                        return 'Rp ' . number_format($state, 0, ',', '.');
                    }),
                Tables\Columns\TextColumn::make('description')
                    ->limit(100)
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make()
            ])
            ->emptyStateIcon('heroicon-o-archive-box-x-mark')
            ->emptyStateHeading('No Benefits')
            ->emptyStateDescription('Add benefit to this employee first.');
    }

    /**
     * @return Action
     */
    public function createBenefitAction(): Action
    {
        return Action::make('add-benefit')
            ->label('Add Benefit')
            ->icon('heroicon-o-folder-plus')
            ->modal()
            ->form([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\Select::make('type')
                            ->options([
                                'allowance' => 'Allowance',
                                'deduction' => 'Deduction'
                            ])->native(false)
                            ->required(),
                        Forms\Components\Select::make('amount_type')
                            ->options([
                                'fixed' => 'Fixed Amount',
                                'percentage' => 'Percentage'
                            ])
                            ->native(false)
                            ->live()
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->helperText(fn(Get $get) => $get('amount_type') === 'percentage' ? 'If amount type is percentage, the amount will be calculated with base salary.' : null)
                            ->numeric()
                            ->required()
                            ->rules(function ($set, $get) {
                                if ($get('amount_type') === 'percentage') {
                                    return ['max:100'];
                                }
                            })
                    ]),
                Forms\Components\Textarea::make('description'),
                Forms\Components\Checkbox::make('is_taxable')

            ])->action(fn($data) => EmployeeService::addBenefit($this->record, $data));
    }

    public function getActions(): array
    {
        return [
            $this->createBenefitAction(),
        ];
    }
}
