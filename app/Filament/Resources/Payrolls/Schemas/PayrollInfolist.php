<?php

namespace App\Filament\Resources\Payrolls\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class PayrollInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Employee Details')
                    ->schema([
                        TextEntry::make('employee.full_name')
                            ->label('Employee Name'),

                        TextEntry::make('employment_status')
                            ->label('Employment Status')
                            ->badge(),
                    ])
                    ->columns(2),

                  Section::make('Summary of Deductions and Net Pay')
                    ->schema([
                        TextEntry::make('total_deductions')
                            ->label('Total Deductions')
                            ->numeric(),

                        TextEntry::make('net_pay')
                            ->label('Net Pay')
                            ->numeric()
                            ->badge()
                            ->color('success'),
                    ])
                    ->columns(2),


                   Section::make('Salary Details')
                    ->schema([
                        TextEntry::make('basic_salary')
                            ->label('Basic Salary')
                            ->numeric(),

                        TextEntry::make('overtime_salary')
                            ->label('Overtime Salary')
                            ->numeric(),

                        TextEntry::make('holiday_pay')
                            ->label('Holiday Pay')
                            ->numeric(),

                        TextEntry::make('other_earnings')
                            ->label('Other Earnings')
                            ->numeric(),

                        TextEntry::make('gross_pay')
                            ->label('Gross Pay')
                            ->numeric(),
                    ])
                    ->columns(5),
                
                Section::make('Attendance Details')
                    ->schema([
                        TextEntry::make('days_present')
                            ->label('Days Present')
                            ->numeric(),

                        TextEntry::make('days_absent')
                            ->label('Days Absent')
                            ->numeric(),

                        TextEntry::make('undertime_hours')
                            ->label('Undertime Hours')
                            ->numeric(),

                        TextEntry::make('daily_rate')
                            ->label('Daily Rate')
                            ->numeric(),
                    ])
                    ->columns(4)
                    ->collapsible()
                    ->collapsed(),

             

                Section::make('Deduction Details')
                    ->schema([
                       
                    Section::make('Other Deductions')
                    ->schema([
                         TextEntry::make('cash_advance')
                            ->label('Cash Advance')
                            ->numeric(),
                         TextEntry::make('shortages')
                            ->label('Shortages')
                            ->numeric(),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->collapsed(),

                     Section::make('SSS Contribution Details')
                    ->schema([
                         TextEntry::make('sss_er')
                            ->label('SSS ER')
                            ->numeric(),
                         TextEntry::make('sss_ee')
                            ->label('SSS EE')
                            ->numeric(),
                        TextEntry::make('sss_loan')
                            ->label('SSS Loan')
                            ->numeric(),
                            ])
                            ->columns(3)
                            ->collapsible()
                            ->collapsed(),

                    Section::make('Pag-IBIG Contribution Details')
                            ->schema([
                                TextEntry::make('pagibig_er')
                                    ->label('Pag-IBIG ER')
                                    ->numeric(),

                                TextEntry::make('pagibig_ee')
                                    ->label('Pag-IBIG EE')
                                    ->numeric(),

                                TextEntry::make('pagibig_loan')
                                    ->label('Pag-IBIG Loan')
                                    ->numeric(),
                            ])
                            ->columns(3)
                            ->collapsible()
                            ->collapsed(),

                        Section::make('PhilHealth Contribution Details')
                            ->schema([
                                TextEntry::make('philhealth_er')
                                    ->label('PhilHealth ER')
                                    ->numeric(),

                                TextEntry::make('philhealth_ee')
                                    ->label('PhilHealth EE')
                                    ->numeric(),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->collapsed(),

                    ])
                    ->columns(1),

              

            ])->columns(1);
    }
}
