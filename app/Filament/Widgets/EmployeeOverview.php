<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Employee;
use App\Models\PayrollPeriod;
use App\Models\Payroll;

class EmployeeOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        // Get the most recent finalized payroll period (by end_date)
        $latestFinalizedPeriod = PayrollPeriod::where('status', 'finalized')
            ->orderByDesc('end_date')
            ->first();

        $totalBasicSalary = 0;
        $totalOvertimeSalary = 0;
        $periodDescription = 'No finalized payroll period';

        if ($latestFinalizedPeriod) {
            $periodDescription = $latestFinalizedPeriod->description;

            $payrollQuery = Payroll::where('payroll_period_id', $latestFinalizedPeriod->id);

            $totalBasicSalary = $payrollQuery->sum('basic_salary');
            $totalOvertimeSalary = $payrollQuery->sum('overtime_salary');
        }

        return [
            Stat::make('Total Employees', Employee::count())
                ->description('All registered employees')
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make(
                'Latest Finalized Payroll Period',
                $periodDescription
            )
                ->icon('heroicon-o-calendar-days')
                ->description('Most recent finalized payroll period only')
                ->color('warning'),

            Stat::make(
                'Total Basic Salary (Latest Finalized)',
                '₱' . number_format($totalBasicSalary, 2)
            )
                ->icon('heroicon-o-banknotes')
                ->description('Sum of basic salary for latest finalized period')
                ->color('success'),

            Stat::make(
                'Total Overtime Salary (Latest Finalized)',
                '₱' . number_format($totalOvertimeSalary, 2)
            )
                ->icon('heroicon-o-clock')
                ->description('Sum of overtime salary for latest finalized period')
                ->color('info'),
        ];
    }
}
