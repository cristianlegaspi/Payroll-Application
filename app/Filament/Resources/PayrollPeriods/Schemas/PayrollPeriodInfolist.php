<?php

namespace App\Filament\Resources\PayrollPeriods\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PayrollPeriodInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Payroll Details')
                    ->schema([
                        TextEntry::make('description'),
                        TextEntry::make('status')->badge(),
                    ]),

                Section::make('Period Dates')
                    ->schema([
                        TextEntry::make('start_date')->date()
                         ->label('Start Date'),
                        TextEntry::make('end_date')->date()
                           ->label('End Date'),
                    ]),

            ])->columns(2);
    }
}
