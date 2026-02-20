<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Employee;
use App\Models\PayrollPeriod;
use App\Models\Payroll;

class EmployeeOverview extends StatsOverviewWidget
{
    protected function getColumns(): int
    {
        return 3; // Max 3 stats per row
    }

    protected function getStats(): array
    {
        $latestFinalizedPeriod = PayrollPeriod::where('status', 'finalized')
            ->orderByDesc('end_date')
            ->first();

        $periodDescription = 'No finalized payroll period';

        $totals = [
            'basic_salary' => 0,
            'overtime_salary' => 0,
            'holiday_pay' => 0,
            'other_earnings' => 0,
            'gross_pay' => 0,
            'total_deductions' => 0,
            'net_pay' => 0,
        ];

        if ($latestFinalizedPeriod) {
            $periodDescription = $latestFinalizedPeriod->description;

            $totals = Payroll::where('payroll_period_id', $latestFinalizedPeriod->id)
                ->selectRaw('
                    SUM(basic_salary) as basic_salary,
                    SUM(overtime_salary) as overtime_salary,
                    SUM(holiday_pay) as holiday_pay,
                    SUM(other_earnings) as other_earnings,
                    SUM(gross_pay) as gross_pay,
                    SUM(total_deductions) as total_deductions,
                    SUM(net_pay) as net_pay
                ')
                ->first()
                ->toArray();
        }

        return [

            Stat::make('Total Employees', Employee::count())
                ->description('All registered employees')
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Latest Finalized Payroll Period', $periodDescription)
                ->icon('heroicon-o-calendar-days')
                ->description('Most recent finalized payroll period only')
                ->color('warning'),

            Stat::make(
                'Total Basic Salary',
                '₱' . number_format($totals['basic_salary'] ?? 0, 2)
            )
                ->description('Latest finalized period')
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make(
                'Total Overtime Salary',
                '₱' . number_format($totals['overtime_salary'] ?? 0, 2)
            )
                ->description('Latest finalized period')
                ->icon('heroicon-o-clock')
                ->color('info'),

            Stat::make(
                'Total Holiday Pay',
                '₱' . number_format($totals['holiday_pay'] ?? 0, 2)
            )
                ->description('Latest finalized period')
                ->icon('heroicon-o-sun')
                ->color('warning'),

            Stat::make(
                'Total Other Earnings',
                '₱' . number_format($totals['other_earnings'] ?? 0, 2)
            )
                ->description('Latest finalized period')
                ->icon('heroicon-o-plus-circle')
                ->color('primary'),

            Stat::make(
                'Total Gross Pay',
                '₱' . number_format($totals['gross_pay'] ?? 0, 2)
            )
                ->description('Before deductions')
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make(
                'Total Deductions',
                '₱' . number_format($totals['total_deductions'] ?? 0, 2)
            )
                ->description('All employee deductions')
                ->icon('heroicon-o-minus-circle')
                ->color('danger'),

            Stat::make(
                'Total Net Pay',
                '₱' . number_format($totals['net_pay'] ?? 0, 2)
            )
                ->description('Final take-home pay')
                ->icon('heroicon-o-check-circle')
                ->color('primary'),
        ];
    }
}