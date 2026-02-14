<?php

namespace App\Filament\Resources\AttendanceRecords\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttendanceRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.full_name')
                    ->searchable(),
                TextColumn::make('payrollPeriod.description')
                    ->searchable(),
                TextColumn::make('days_present')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('absences')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('undertime_hours')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('overtime_hours')
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
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
