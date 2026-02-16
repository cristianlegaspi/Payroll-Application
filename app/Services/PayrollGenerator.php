<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\Payroll;
use App\Models\PayrollPeriod;
use Illuminate\Support\Facades\DB;
use Exception;

class PayrollGenerator
{
    public static function generate(int $payrollPeriodId): void
    {
        DB::transaction(function () use ($payrollPeriodId) {

            $records = AttendanceRecord::with('employee')
                ->where('payroll_period_id', $payrollPeriodId)
                ->get();

            if ($records->isEmpty()) {
                throw new Exception('No attendance data found. Payroll not generated.');
            }

            foreach ($records as $record) {

                $employee = $record->employee;
                if (!$employee) continue;

                $dailyRate = (float) ($employee->daily_rate ?? 0);
                $absences = (float) ($record->absences ?? 0);
                $undertimeHours = (float) ($record->undertime_hours ?? 0);

                $basicSalary = (float) ($record->basic_salary ?? 0);
                $overtimeSalary = (float) ($record->overtime_salary ?? 0);
                $holidayPay = (float) ($record->holiday_pay ?? 0);
                $grossPay = (float) ($record->gross_pay ?? 0);

                // =========================
                // CONTRIBUTIONS
                // =========================

                $sssER = (float) ($employee->sss_er ?? 0);
                $sssEE = (float) ($employee->sss_ee ?? 0);
                $sssLoan = (float) ($employee->sss_loan ?? 0);
                $sssTotal = $sssEE + $sssLoan; // ONLY EE + Loan deducted

                $philhealthER = (float) ($employee->philhealth_er ?? 0);
                $philhealthEE = (float) ($employee->philhealth_ee ?? 0);

                $pagibigER = (float) ($employee->pagibig_er ?? 0);
                $pagibigEE = (float) ($employee->pagibig_ee ?? 0);
                $pagibigLoan = (float) ($employee->pagibig_loan ?? 0);
                $pagibigTotal = $pagibigEE + $pagibigLoan; // ONLY EE + Loan deducted

                $cashAdvance = (float) ($employee->cash_advance ?? 0);
                $otherDeductions = (float) ($employee->other_deductions ?? 0);

                // =========================
                // TOTAL DEDUCTIONS
                // =========================

                $totalDeductions =
                    $sssTotal +
                    $philhealthEE +
                    $pagibigTotal +
                    $cashAdvance +
                    $otherDeductions;

                $netPay = round($grossPay - $totalDeductions, 2);

                Payroll::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'payroll_period_id' => $payrollPeriodId,
                    ],
                    [
                        'employment_status' => $employee->employment_status,
                        'position' => $employee->position,
                        'daily_rate' => $dailyRate,

                        'days_present' => $record->days_present,
                        'days_absent' => $absences,
                        'undertime_hours' => $undertimeHours,

                        'basic_salary' => $basicSalary,
                        'overtime_salary' => $overtimeSalary,
                        'holiday_pay' => $holidayPay,
                        'gross_pay' => $grossPay,

                        // =========================
                        // SAVE CONTRIBUTIONS PROPERLY
                        // =========================
                        'sss_er' => $sssER,
                        'sss_ee' => $sssEE,
                        'sss_loan' => $sssLoan,
                        'sss_total' => $sssTotal,

                        'philhealth_er' => $philhealthER,
                        'philhealth_ee' => $philhealthEE,

                        'pagibig_er' => $pagibigER,
                        'pagibig_ee' => $pagibigEE,
                        'pagibig_loan' => $pagibigLoan,
                        'pagibig_total' => $pagibigTotal,

                        'cash_advance' => $cashAdvance,
                        'shortages' => $otherDeductions,

                        'total_deductions' => $totalDeductions,
                        'net_pay' => $netPay,

                        'status' => 'generated',
                    ]
                );
            }

            PayrollPeriod::where('id', $payrollPeriodId)
                ->update(['status' => 'finalized']);
        });
    }
}
