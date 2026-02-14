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

            // ✅ VALIDATION: Check if there are attendance records
            if ($records->isEmpty()) {
                throw new Exception('No attendance data found. Payroll not generated.');
            }

            foreach ($records as $record) {

                $employee = $record->employee;
                if (!$employee) continue;

                $dailyRate = (float) ($employee->daily_rate ?? 0);

                // Ensure absences and undertime_hours are numeric
                $absences = (float) ($record->absences ?? 0);
                $undertimeHours = (float) ($record->undertime_hours ?? 0);

                $basicSalary = (float) ($record->basic_salary ?? 0);
                $overtimeSalary = (float) ($record->overtime_salary ?? 0);
                $holidayPay = (float) ($record->holiday_pay ?? 0);
                $grossPay = (float) ($record->gross_pay ?? 0); // ✅ leave gross pay unchanged

                // ✅ Compute absence deduction (for reference only, do not subtract)
                $absenceDeduction = $absences * $dailyRate;

                $sssEE = (float) ($employee->sss_ee ?? 0);
                $philhealthEE = (float) ($employee->philhealth_ee ?? 0);
                $pagibigEE = (float) ($employee->pagibig_ee ?? 0);

                $sssLoan = (float) ($employee->sss_loan ?? 0);
                $pagibigLoan = (float) ($employee->pagibig_loan ?? 0);

                $cashAdvance = (float) ($employee->cash_advance ?? 0);
                $otherDeductions = (float) ($employee->other_deductions ?? 0);

                // Total deductions exclude absence deduction
                $totalDeductions =
                    $sssEE +
                    $philhealthEE +
                    $pagibigEE +
                    $sssLoan +
                    $pagibigLoan +
                    $cashAdvance +
                    $otherDeductions;

                $netPay = round($grossPay - $totalDeductions, 2);

              
                // dd([
                //     'employee_id' => $employee->id,
                //     'absences' => $absences,
                //     'daily_rate' => $dailyRate,
                //     'absence_deduction' => $absenceDeduction,
                //     'gross_pay' => $grossPay,
                //     'total_deductions' => $totalDeductions,
                //     'net_pay' => $netPay,
                //     'undertime_hours' => $undertimeHours,
                // ]);

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
                        'gross_pay' => $grossPay, // unchanged

                        'sss_er' => $employee->sss_er,
                        'sss_ee' => $sssEE,
                        'sss_loan' => $sssLoan,

                        'philhealth_er' => $employee->philhealth_er,
                        'philhealth_ee' => $philhealthEE,

                        'pagibig_er' => $employee->pagibig_er,
                        'pagibig_ee' => $pagibigEE,
                        'pagibig_loan' => $pagibigLoan,

                        'cash_advance' => $cashAdvance,
                        'other_deductions' => $otherDeductions,
                        'absence_deduction' => $absenceDeduction, // tracked separately
                        'total_deductions' => $totalDeductions,
                        'net_pay' => $netPay, // unchanged

                        'status' => 'generated',
                    ]
                );
            }

            // ✅ FINALIZE ONLY IF DATA EXISTS
            PayrollPeriod::where('id', $payrollPeriodId)
                ->update(['status' => 'finalized']);
        });
    }
}
