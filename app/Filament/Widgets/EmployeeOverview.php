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
        // Get IDs of currently open payroll periods
       $openPeriodIds = PayrollPeriod::where('status', 'finalized')->pluck('id');
        $totalGrossPay = Payroll::whereIn('payroll_period_id', $openPeriodIds)->sum('gross_pay');
        // Get description of first open period (or fallback)
        $activePeriodDesc = PayrollPeriod::where('status', 'open')
            ->value('description') ?? 'No open payroll period';

        return [
            Stat::make('Total Employees', Employee::count())
                ->description('All registered employees')
                ->icon('heroicon-o-users')
                ->color('primary'),

            // Stat::make('Active Payroll Period', $activePeriodDesc)
            //     ->icon('heroicon-o-calendar-days')
            //     ->description('Currently active payroll period')
            //     ->color('warning'),

            // Stat::make(
            //     'Total Gross Pay',
            //     '₱' . number_format($totalGrossPay, 2)
            // )
            //     ->icon('heroicon-o-currency-dollar')
            //     ->description('Sum of gross pay for open payroll periods')
            //     ->color('success'),
        ];
    }
}
