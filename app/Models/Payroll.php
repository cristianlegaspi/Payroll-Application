<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [

        'employee_id',
        'payroll_period_id',

        'employment_status',
        'position',

        'days_present',
        'days_absent',
        'undertime_hours',

        'daily_rate',

        'basic_salary',
        'overtime_salary',
        'holiday_pay',
        'other_earnings',
        'gross_pay',

        'cash_advance',
        'shortages',

        'sss_er',
        'sss_ee',
        'sss_loan',
        'sss_total',

        'philhealth_er',
        'philhealth_ee',

        'pagibig_er',
        'pagibig_ee',
        'pagibig_loan',
        'pagibig_total',

        'total_deductions',
        'net_pay',

        'status',
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payrollPeriod()
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    protected static function booted()
    {
        static::saving(function ($payroll) {

            $grossPay = (float) $payroll->gross_pay;

            $sssEE = (float) ($payroll->sss_ee ?? 0);
            $philhealthEE = (float) ($payroll->philhealth_ee ?? 0);
            $pagibigEE = (float) ($payroll->pagibig_ee ?? 0);

            $sssLoan = (float) ($payroll->sss_loan ?? 0);
            $pagibigLoan = (float) ($payroll->pagibig_loan ?? 0);

            $cashAdvance = (float) ($payroll->cash_advance ?? 0);
            $shortages = (float) ($payroll->shortages ?? 0);

            $payroll->total_deductions =
                $sssEE +
                $philhealthEE +
                $pagibigEE +
                $sssLoan +
                $pagibigLoan +
                $cashAdvance +
                $shortages;

            $payroll->net_pay = round(
                $grossPay - $payroll->total_deductions,
                2
            );
        });
    }
}
