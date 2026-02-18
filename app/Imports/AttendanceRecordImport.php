<?php

namespace App\Imports;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use App\Models\PayrollPeriod;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendanceRecordImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $employee = Employee::where('employee_number', $row['employee_number'])->first();
        $payrollPeriod = PayrollPeriod::where('description', $row['description'])->first();

        if (! $employee || ! $payrollPeriod) {
            return null; // skip if not found
        }

        return AttendanceRecord::updateOrCreate(
            [
                'employee_id'       => $employee->id,
                'payroll_period_id' => $payrollPeriod->id,
            ],
            [
                'days_present'         => (float) ($row['days_present'] ?? 0),
                'absences'             => (float) ($row['absences'] ?? 0),
                'undertime_hours'      => (float) ($row['undertime_hours'] ?? 0),
                'overtime_hours'       => (float) ($row['overtime_hours'] ?? 0),
                'sunday_ot_hours'      => (float) ($row['sunday_ot_hours'] ?? 0),
                'sunday_days'          => (float) ($row['sunday_days'] ?? 0),
                'regular_holiday_days' => (float) ($row['regular_holiday_days'] ?? 0),
                'special_holiday_days' => (float) ($row['special_holiday_days'] ?? 0),
            ]
        );
    }
}
