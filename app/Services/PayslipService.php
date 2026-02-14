<?php

namespace App\Services;

use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;

class PayslipService
{
    /**
     * Generate payslip PDF for a single payroll.
     */
    public static function generate(Payroll $payroll)
    {
        $dailyRate = (float) ($payroll->daily_rate ?? 0);

        $data = [
            'company' => 'E.A OCAMPO ENTERPRISES',
            'payroll_period' => $payroll->payrollPeriod->description ?? 'N/A',
            'employee_name' => $payroll->employee->full_name ?? 'N/A',
            'position' => $payroll->position,
            'daily_rate' => $dailyRate,
            'days_worked' => $payroll->days_present,
            'days_absent' => $payroll->days_absent,
            'undertime_hours' => $payroll->undertime_hours,
            'total' => $payroll->basic_salary,
            'additions' => [
                'holiday_ot' => $payroll->holiday_pay,
                'other' => $payroll->other_earnings ?? 0,
            ],
            'total_salary' => $payroll->gross_pay,
            'deductions' => [
                'sss' => $payroll->sss_ee,
                'philhealth' => $payroll->philhealth_ee,
                'pagibig' => $payroll->pagibig_ee,
                'loan' => ($payroll->sss_loan ?? 0) + ($payroll->pagibig_loan ?? 0),
                'advances' => $payroll->cash_advance ?? 0,
                'shortages' => $payroll->shortages ?? 0,
                'undertime' => $payroll->undertime_hours * ($dailyRate / 8),
                'absence' => $payroll->absence_deduction ?? 0, // ✅ include absence deduction
            ],
            'total_deductions' => $payroll->total_deductions,
            'net_pay' => $payroll->net_pay,
            'date' => now()->format('M/d/Y'),
        ];

        $pdf = Pdf::loadView('payslip.pdf', $data);

        return $pdf->stream("Payslip_{$payroll->employee->full_name}_{$payroll->payrollPeriod->description}.pdf");
    }
}
