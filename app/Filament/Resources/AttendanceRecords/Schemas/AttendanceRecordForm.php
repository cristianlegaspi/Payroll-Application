<?php

namespace App\Filament\Resources\AttendanceRecords\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;


class AttendanceRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
             
            
                Section::make('Payroll Information Details')
                    ->schema([

                    Select::make('payroll_period_id')
                        ->relationship(
                            'payrollPeriod',
                            'description',
                            fn ($query) => $query->where('status', 'open')
                        )
                        ->required()
                        ->label('Payroll Period'),


                    Select::make('employee_id')
                         ->relationship('employee', 'full_name')
                         ->required()
                         ->label('Employee Name'),
                    ])->columns(2),

                   Section::make('Attendance Information Details')
                    ->schema([
                         TextInput::make('days_present')
                            ->required()
                            ->label('DAYS PRESENT')
                            ->numeric()
                            ->default(0),
                        TextInput::make('absences')
                            ->required()
                            ->label('DAYS ABSENT')
                            ->numeric()
                            ->step(0.01)
                            ->default(0),
                        TextInput::make('undertime_hours')
                            ->required()
                            ->label('HOURS UNDERTIME')
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('overtime_hours')
                            ->required()
                            ->label('OVERTIME HOURS (MONDAY-SATURDAY)')
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('sunday_ot_hours')
                            ->required()
                            ->numeric()
                            ->label('OVERTIME HOURS (SUNDAY)')
                            ->default(0),
                        TextInput::make('sunday_days')
                            ->required()
                            ->label('SUNDAY DAYS')
                            ->numeric()
                            ->default(0),
                        TextInput::make('regular_holiday_days')
                            ->required()
                            ->label('REGULAR HOLIDAY DAYS')
                            ->numeric()
                            ->default(0),
                        TextInput::make('special_holiday_days')
                            ->required()
                            ->numeric()
                            ->label('SPECIAL HOLIDAY DAYS')
                            ->default(0),
                    ])->columns(3),           
            ])->columns(1);
    }
}
