<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceTemplateExport implements FromCollection, WithHeadings
{
    protected $employees;
    protected $payrollDescription;
    protected $branchName;

    public function __construct($employees, $payrollDescription, $branchName = null)
    {
        $this->employees = $employees;
        $this->payrollDescription = $payrollDescription;
        $this->branchName = $branchName;
    }

    public function collection()
    {
        return $this->employees->map(function ($employee) {
            return [
                'description'           => $this->payrollDescription,
                'employee_number'       => $employee->employee_number,
                'full_name'             => $employee->full_name, // using full_name column
                'branch_name'           => $employee->branch_name,
                'days_present'          => '',
                'absences'              => '',
                'undertime_hours'       => '',
                'overtime_hours'        => '',
                'sunday_ot_hours'       => '',
                'sunday_days'           => '',
                'regular_holiday_days'  => '',
                'special_holiday_days'  => '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'description',
            'employee_number',
            'full_name',       // updated
            'branch_name',
            'days_present',
            'absences',
            'undertime_hours',
            'overtime_hours',
            'sunday_ot_hours',
            'sunday_days',
            'regular_holiday_days',
            'special_holiday_days',
        ];
    }
}
