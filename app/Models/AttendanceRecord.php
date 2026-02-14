<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    protected $fillable = [
        'employee_id',
        'payroll_period_id',
        'days_present',
        'absences',
        'undertime_hours',
        'overtime_hours',
        'sunday_ot_hours',
        'sunday_days',
        'regular_holiday_days',
        'special_holiday_days',
    ];

    protected $casts = [
        'undertime_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'sunday_ot_hours' => 'decimal:2',
        'sunday_days' => 'decimal:2',
        'regular_holiday_days' => 'decimal:2',
        'special_holiday_days' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function payrollPeriod(): BelongsTo
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Payroll Computations
    |--------------------------------------------------------------------------
    */

    // Hourly Rate
    public function getHourlyRateAttribute(): float
    {
        $dailyRate = $this->employee?->daily_rate ?? 0;

        return round($dailyRate / 8, 2);
    }

    // Absence Deduction
    public function getAbsenceDeductionAttribute(): float
    {
        return round(
            $this->absences * ($this->employee?->daily_rate ?? 0),
            2
        );
    }

    // Undertime Deduction
    public function getUndertimeDeductionAttribute(): float
    {
        return round(
            $this->undertime_hours * $this->hourly_rate,
            2
        );
    }

    // Basic Salary (FINAL FIXED LOGIC)
    public function getBasicSalaryAttribute(): float
    {
        $dailyRate = $this->employee?->daily_rate ?? 0;

        $presentPay = $this->days_present * $dailyRate;

        return round(
            $presentPay
            - $this->absence_deduction
            - $this->undertime_deduction,
            2
        );
    }

    // Overtime Salary
    public function getOvertimeSalaryAttribute(): float
    {
        $hourly = $this->hourly_rate;
        $dailyRate = $this->employee?->daily_rate ?? 0;

        $regularOT = $this->overtime_hours * $hourly * 1.25;
        $sundayOT = $this->sunday_ot_hours * $hourly * 1.30;
        $sundayPremium = $this->sunday_days * $dailyRate * 0.30;

        return round($regularOT + $sundayOT + $sundayPremium, 2);
    }

    // Holiday Pay
    public function getHolidayPayAttribute(): float
    {
        $dailyRate = $this->employee?->daily_rate ?? 0;

        $regular = $this->regular_holiday_days * $dailyRate;
        $special = $this->special_holiday_days * $dailyRate * 0.30;

        return round($regular + $special, 2);
    }

    // Gross Pay
    public function getGrossPayAttribute(): float
    {
        return round(
            $this->basic_salary
            + $this->overtime_salary
            + $this->holiday_pay,
            2
        );
    }
}
