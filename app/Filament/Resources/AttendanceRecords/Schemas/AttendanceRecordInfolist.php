<?php

namespace App\Filament\Resources\AttendanceRecords\Schemas;

use Dom\Text;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class AttendanceRecordInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Employee and Payroll Details')
                    ->schema([
                        TextEntry::make('payrollPeriod.description')
                            ->label('Payroll period'),

                        TextEntry::make('employee.full_name')
                            ->label('Employee Name'),
                    ])->columnSpanFull()->columns(2),

                Section::make('Attendance Information Details')
                    ->schema([

                        TextEntry::make('days_present')
                            ->numeric()
                            ->label('NO. OF DAYS'),
                        TextEntry::make('absences')
                            ->numeric()
                            ->label('DAYS ABSENT'),
                        TextEntry::make('undertime_hours')
                            ->numeric()
                            ->label('HOURS UNDERTIME'),
                        TextEntry::make('overtime_hours')
                            ->numeric()
                            ->label('HOURS OVERTIME'),
                        TextEntry::make('sunday_ot_hours')
                            ->numeric()
                            ->label('SUNDAY OT HOURS'),
                        TextEntry::make('sunday_days')
                            ->numeric()
                            ->label('SUNDAY DAYS'),
                        TextEntry::make('regular_holiday_days')
                            ->numeric()
                            ->label('REGULAR HOLIDAY DAYS'),
                        TextEntry::make('special_holiday_days')
                            ->numeric()
                            ->label('SPECIAL HOLIDAY DAYS'),
                    ])->collapsible()->collapsed()->columns(4),        
            ])->columns(1);
    }
}
