<?php

namespace App\Imports;

use App\Models\AttendanceRecord;
use App\Models\User;
use App\Models\Employee;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\PayrollPeriod;

class AttendanceRecordImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
{
    
 
     $employee = Employee::where('employee_number', $row['employee_number'])->first();

    if (! $employee) {
        return null;
    }

    $payrollPeriod = PayrollPeriod::where('description', $row['description'])->first();

    if (! $payrollPeriod) {
        return null;
    }

    return AttendanceRecord::updateOrCreate(
        [
            'employee_id'       => $employee->id,
            'payroll_period_id' => $payrollPeriod->id,
        ],
        [
            'days_present'          => $row['days_present'],
            'absences'              => $row['absences'],
            'undertime_hours'       => $row['undertime_hours'],
            'overtime_hours'        => $row['overtime_hours'],
            'sunday_ot_hours'       => $row['sunday_ot_hours'],
            'sunday_days'           => $row['sunday_days'],
            'regular_holiday_days'  => $row['regular_holiday_days'],
            'special_holiday_days'  => $row['special_holiday_days'],
        ]
    );
}
}
