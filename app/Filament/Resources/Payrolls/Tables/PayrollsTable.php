<?php

namespace App\Filament\Resources\Payrolls\Tables;

use App\Models\PayrollPeriod;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table

            // ✅ Do not show any records if no Payroll Period selected
            // ->modifyQueryUsing(function (Builder $query, $livewire) {
            //     $selectedPeriod = $livewire->tableFilters['payroll_period_id']['value'] ?? null;

            //     if (! $selectedPeriod) {
            //         $query->whereRaw('1 = 0'); // return empty
            //     }
            // })

            ->columns([
                TextColumn::make('employee.full_name')
                    ->label('Employee')
                    ->sortable(),

                TextColumn::make('payrollPeriod.description')
                    ->label('Payroll Period')
                    ->sortable(),

                TextColumn::make('employment_status')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('position')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('days_present')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('days_absent')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('undertime_hours')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('daily_rate')
                    ->money('PHP')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('basic_salary')
                    ->money('PHP')
                    ->sortable(),

                TextColumn::make('overtime_salary')
                    ->money('PHP')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('holiday_pay')
                    ->money('PHP')
                    ->sortable(),

                TextColumn::make('other_earnings')
                    ->money('PHP')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('gross_pay')
                    ->money('PHP')
                    ->sortable(),

                TextColumn::make('cash_advance')
                    ->money('PHP')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('shortages')
                    ->money('PHP')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('sss_total')
                    ->money('PHP')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('philhealth_ee')
                    ->money('PHP')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('pagibig_total')
                    ->money('PHP')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('total_deductions')
                    ->label('Total Deductions')
                    ->money('PHP')
                    ->badge()
                    ->color('danger')
                    ->sortable(),

                TextColumn::make('net_pay')
                    ->money('PHP')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                TextColumn::make('status')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            // ✅ Payroll Period Filter
            ->filters([
                SelectFilter::make('payroll_period_id')
                    ->label('Payroll Period')
                    ->options(
                        PayrollPeriod::pluck('description', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->query(function (Builder $query, array $data) {
                        if (filled($data['value'])) {
                            $query->where('payroll_period_id', $data['value']);
                        }
                    }),
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
