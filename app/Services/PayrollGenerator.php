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
                $holidayPay = (float) ($record->holiday_pay ?? 0);
                $otherEarnings = (float) ($record->other_earnings ?? 0);

                // =========================
                // ✅ FIXED OVERTIME LOGIC
                // =========================
                $overtimeHours = (float) ($record->overtime_hours ?? 0);

                $overtimeSalary = $overtimeHours > 0
                    ? (float) ($record->overtime_salary ?? 0)
                    : 0;

                // Recalculate gross pay safely
                $grossPay =
                    $basicSalary +
                    $overtimeSalary +
                    $holidayPay +
                    $otherEarnings;

                // =========================
                // CONTRIBUTIONS
                // =========================
                $sssER = (float) ($employee->sss_er ?? 0);
                $sssEE = (float) ($employee->sss_ee ?? 0);
                $sssSalaryLoan = (float) ($employee->sss_salary_loan ?? 0);
                $sssCalamityLoan = (float) ($employee->sss_calamity_loan ?? 0);
                $premiumVoluntarySS = (float) ($employee->premium_voluntary_ss_contribution ?? 0);

                $sssTotal = $sssEE + $sssSalaryLoan + $sssCalamityLoan + $premiumVoluntarySS;

                $philhealthER = (float) ($employee->philhealth_er ?? 0);
                $philhealthEE = (float) ($employee->philhealth_ee ?? 0);

                $pagibigER = (float) ($employee->pagibig_er ?? 0);
                $pagibigEE = (float) ($employee->pagibig_ee ?? 0);
                $pagibigSalaryLoan = (float) ($employee->pagibig_salary_loan ?? 0);
                $pagibigTotal = $pagibigEE + $pagibigSalaryLoan;

                $cashAdvance = (float) ($employee->cash_advance ?? 0);
                $shortages = (float) ($employee->shortages ?? 0);

                // =========================
                // TOTAL DEDUCTIONS
                // =========================
                $totalDeductions =
                    $sssTotal +
                    $philhealthEE +
                    $pagibigTotal +
                    $cashAdvance +
                    $shortages;

                $netPay = round($grossPay - $totalDeductions, 2);

                // =========================
                // SAVE TO PAYROLL
                // =========================
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
                        'other_earnings' => $otherEarnings,
                        'gross_pay' => $grossPay,

                        // SSS
                        'sss_er' => $sssER,
                        'sss_ee' => $sssEE,
                        'sss_salary_loan' => $sssSalaryLoan,
                        'sss_calamity_loan' => $sssCalamityLoan,
                        'premium_voluntary_ss_contribution' => $premiumVoluntarySS,
                        'sss_total' => $sssTotal,

                        // PhilHealth
                        'philhealth_er' => $philhealthER,
                        'philhealth_ee' => $philhealthEE,

                        // Pag-IBIG
                        'pagibig_er' => $pagibigER,
                        'pagibig_ee' => $pagibigEE,
                        'pagibig_salary_loan' => $pagibigSalaryLoan,
                        'pagibig_total' => $pagibigTotal,

                        // Other deductions
                        'cash_advance' => $cashAdvance,
                        'shortages' => $shortages,

                        'total_deductions' => $totalDeductions,
                        'net_pay' => $netPay,

                        'status' => 'generated',
                    ]
                );
            }

            // Finalize payroll period
            PayrollPeriod::where('id', $payrollPeriodId)
                ->update(['status' => 'finalized']);
        });
    }
}
