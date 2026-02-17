<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([


            Section::make('Employee Details')
                    ->schema([
                        TextEntry::make('employee_number')
                         ->label('Employee Number'),
                        TextEntry::make('full_name')
                         ->label('Complete Name'),
                        TextEntry::make('position'),
                        TextEntry::make('branch_name'),
                         TextEntry::make('date_of_birth')
                         ->label('Date of Birth')
                        ->date(),
                        TextEntry::make('employment_status')
                         ->label('Employement Status')
                        ->badge(),
                    ])->columns(2),
           
            Section::make('Employee Additional Information')
                    ->schema([
                        TextEntry::make('daily_rate')
                         ->label('Daily Rate (PHP)')
                        ->numeric(),
                        TextEntry::make('date_hired')
                         ->label('Date Hired')
                        ->date(),
                        TextEntry::make('employee_type')
                        ->label('Employment Type')
                        ->badge(),
                        TextEntry::make('status')
                        ->label('Status')
                        ->badge(),
                        TextEntry::make('tin')
                        ->label('Tax Identification Number (TIN)')
                        ->placeholder('-'),
                    ])->columns(2),

            Section::make('SSS Contribution Details')
                    ->schema([
                        TextEntry::make('sss_ee')
                            ->label('EE')
                            ->numeric()     
                            ->default(0.0),
                        TextEntry::make('sss_er')
                            ->label('ER')       
                            ->numeric()
                            ->default(0.0),
                        TextEntry::make('sss_loan')
                            ->label('Loan')
                            ->numeric()
                            ->default(0.0),
                    ])->collapsible()->collapsed()->columns(3),         
            
            Section::make('PhilHealth Contribution Details')
                    ->schema([
                        TextEntry::make('philhealth_ee')
                            ->label('EE')
                            ->numeric()
                            ->default(0.0),
                        TextEntry::make('philhealth_er')
                            ->label('ER')
                            ->numeric()
                            ->default(0.0),
                    ])->collapsible()->collapsed()->columns(2),
            
            Section::make('PAGIBIG Contribution Details')
                    ->schema([
                        TextEntry::make('pagibig_ee')
                            ->label('EE')
                            ->numeric()
                            ->default(0.0),
                        TextEntry::make('pagibig_er')           
                            ->label('ER')
                            ->numeric()
                            ->default(0.0),
                        TextEntry::make('pagibig_loan')
                             ->label('Loan')                
                            ->numeric()         
                            ->default(0.0),             
                    ])->collapsible()->collapsed()->columns(3),
            ])->columns(2);
    }
}
