<?php

namespace App\Filament\Resources\PayrollPeriods;

use App\Filament\Resources\PayrollPeriods\Pages\CreatePayrollPeriod;
use App\Filament\Resources\PayrollPeriods\Pages\EditPayrollPeriod;
use App\Filament\Resources\PayrollPeriods\Pages\ListPayrollPeriods;
use App\Filament\Resources\PayrollPeriods\Pages\ViewPayrollPeriod;
use App\Filament\Resources\PayrollPeriods\Schemas\PayrollPeriodForm;
use App\Filament\Resources\PayrollPeriods\Schemas\PayrollPeriodInfolist;
use App\Filament\Resources\PayrollPeriods\Tables\PayrollPeriodsTable;
use App\Models\PayrollPeriod;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Action;
use App\Services\PayrollPeriodGenerator;
use UnitEnum;

class PayrollPeriodResource extends Resource
{
    protected static ?string $model = PayrollPeriod::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Calendar;

    protected static ?string $recordTitleAttribute = 'PayrollPeriod';

     protected static string | UnitEnum | null $navigationGroup = 'Attendance Management';


    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return PayrollPeriodForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PayrollPeriodInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
          return $table
        ->columns([
             TextColumn::make('description')->label('Period'),
                TextColumn::make('start_date')->date(),
                TextColumn::make('end_date')->date(),
              TextColumn::make('status')
    ->label('Status')
    ->formatStateUsing(fn ($state) => match($state) {
        'open' => 'Open',
        'closed' => 'Closed',
        'processing' => 'Processing',
        'finalized' => 'Finalized',
        default => $state,
    })
    ->sortable()
    ->colors([
        'success' => fn ($state) => $state === 'open',
        'danger' => fn ($state) => $state === 'closed',
        'warning' => fn ($state) => $state === 'processing',
        'info'  => fn ($state) => $state === 'finalized',
    ]),
        ])
        ->headerActions([

            Action::make('generate')
                ->label('Generate Payroll Periods')
                ->icon('heroicon-o-calendar')
                ->form([
                    TextInput::make('year')
                        ->numeric()
                        ->default(now()->year)
                        ->required(),
                ])
                ->action(function (array $data) {

                    PayrollPeriodGenerator::generateForYear($data['year']);

                })
                ->successNotificationTitle('Payroll periods generated successfully'),

        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

     public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayrollPeriods::route('/'),
            'create' => CreatePayrollPeriod::route('/create'),
            // 'view' => ViewPayrollPeriod::route('/{record}'),
            'edit' => EditPayrollPeriod::route('/{record}/edit'),
        ];
    }
}
