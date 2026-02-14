<?php

namespace App\Filament\Resources\PayrollPeriods\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class PayrollPeriodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

             Section::make('Payroll Information')
                    ->schema([

                    TextInput::make('description')
                        ->label('Payroll Period Description')
                        ->placeholder('February 1-15, 2026')
                        ->required(),

                    Select::make('status')
                        ->options(['closed' => 'Closed', 'open' => 'Open', 'processing' => 'Processing', 'finalized' => 'Finalized'])
                        ->default('closed')
                        ->required(),
                    ])->columns(2),

              Section::make('Date Information')
                    ->schema([

                     DatePicker::make('start_date')
                    ->label('Start Date')
                    ->required(),

                     DatePicker::make('end_date')
                    ->label('End Date')
                    ->required(),

                    ])->columns(2),           
            ])->columns(1);
    }
}
