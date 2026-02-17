<?php

namespace App\Filament\Resources\Employees\Tables;

use App\Models\Employee;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee_number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('full_name')
                    ->label('Complete Name')
                    ->searchable(),
                TextColumn::make('position')
                    ->searchable(),
                 TextColumn::make('branch_name')
                 ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('employment_status')
                    ->badge()
                    ->label('Employment Status')
                        ->color(fn(string $state): string => match ($state) {
                            'Probationary' => 'warning',
                            'Regular' => 'success',
                            }),
                TextColumn::make('daily_rate')
                    ->label('Daily Rate')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('date_hired')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('date_of_birth')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->label('Status')
                        ->color(fn(string $state): string => match ($state) {
                            'Resigned' => 'danger',
                            'Active' => 'success',
                            }),
                TextColumn::make('employee_type')
                    ->badge()
                    ->label('Employment Type')
                        ->color(fn(string $state): string => match ($state) {
                            'Field' => 'warning',
                            'Admin' => 'success',
                            }),
                TextColumn::make('tin')
                    ->label('TIN Number')
                    ->searchable(),

                textColumn::make('sss_ee')
                    ->label('SSS Employee Share')
                    ->numeric()
                    ->default(0.0)
                    ->toggleable(isToggledHiddenByDefault: true),

                textColumn::make('sss_er')
                    ->label('SSS Employer Share')
                    ->numeric()
                    ->default(0.0)
                    ->toggleable(isToggledHiddenByDefault: true), 
                textColumn::make('sss_loan')
                    ->label('SSS Loan')
                    ->numeric()
                    ->default(0.0)
                    ->toggleable(isToggledHiddenByDefault: true),   
                textColumn::make('philhealth_ee')
                    ->label('PhilHealth Employee Share')
                    ->numeric()
                    ->default(0.0)
                    ->toggleable(isToggledHiddenByDefault: true),
                textColumn::make('philhealth_er')
                    ->label('PhilHealth Employer Share')
                    ->numeric()
                    ->default(0.0)
                    ->toggleable(isToggledHiddenByDefault: true),
                textColumn::make('pagibig_ee')
                    ->label('Pag-IBIG Employee Share')
                    ->numeric()
                    ->default(0.0)
                    ->toggleable(isToggledHiddenByDefault: true),
                textColumn::make('pagibig_er')
                    ->label('Pag-IBIG Employer Share')
                    ->numeric()
                    ->default(0.0)
                    ->toggleable(isToggledHiddenByDefault: true),
                textColumn::make('pagibig_loan')
                    ->label('Pag-IBIG Loan')
                    ->numeric()
                    ->default(0.0)
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
            ->filters([
             SelectFilter::make('branch_name')
            ->label('Branch Name')
            ->options(
                Employee::query()
                    ->select('branch_name')
                    ->distinct()
                    ->whereNotNull('branch_name')
                    ->pluck('branch_name', 'branch_name')
                    ->toArray()
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
