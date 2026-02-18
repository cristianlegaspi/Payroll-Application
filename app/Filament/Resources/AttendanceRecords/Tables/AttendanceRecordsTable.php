<?php

namespace App\Filament\Resources\AttendanceRecords\Tables;

use App\Models\PayrollPeriod;
use App\Models\Employee;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class AttendanceRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // Default query: show the latest OPEN payroll period
            ->modifyQueryUsing(function (Builder $query) {
                $latestOpenId = PayrollPeriod::where('status', 'open')
                    ->latest('id')
                    ->value('id');

                if ($latestOpenId) {
                    $query->where('payroll_period_id', $latestOpenId);
                }
            })

            ->columns([
                TextColumn::make('employee.full_name')
                    ->label('Employee Name')
                    ->searchable(),

                TextColumn::make('payrollPeriod.description')
                    ->label('Payroll Period')
                    ->searchable(),

                TextColumn::make('days_present')
                    ->label('Days Present')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('absences')
                    ->label('Absences')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('undertime_hours')
                    ->label('Undertime Hours')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('overtime_hours')
                    ->label('Overtime Hours')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('sunday_ot_hours')
                    ->label('Sunday OT Hours')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('sunday_days')
                    ->label('Sunday Days')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('regular_holiday_days')
                    ->label('Regular Holiday Days')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('special_holiday_days')
                    ->label('Special Holiday Days')
                    ->numeric()
                    ->sortable(),
            ])

            ->filters([

            
             

                // Employee Filter
                SelectFilter::make('employee_id')
                    ->label('Employee')
                    ->relationship(
                        'employee',
                        'full_name',
                        fn (Builder $query) => $query->orderBy('full_name')
                    )
                    ->searchable()
                    ->preload(),
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
