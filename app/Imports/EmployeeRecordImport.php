<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeRecordImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 🔹 Debug row: now it will show keys => values
        // dd($row);

        // 🔄 Insert or update employee
        return Employee::updateOrCreate(
            ['employee_number' => $row['employee_number']],
            [
                'full_name'         => $row['full_name'],
                'position'          => $row['position'],
                'employment_status' => $row['employment_status'],
                'daily_rate'        => $row['daily_rate'] ?? 0,
                'date_hired'        => $row['date_hired'],
                'employee_type'     => $row['employee_type'],
                'tin'               => $row['tin'] ?? null,
                'sss_ee'            => $row['sss_ee'] ?? 0,
                'sss_er'            => $row['sss_er'] ?? 0,
                'sss_loan'          => $row['sss_loan'] ?? 0,
                'philhealth_ee'     => $row['philhealth_ee'] ?? 0,
                'philhealth_er'     => $row['philhealth_er'] ?? 0,
                'pagibig_ee'        => $row['pagibig_ee'] ?? 0,
                'pagibig_er'        => $row['pagibig_er'] ?? 0,
                'pagibig_loan'      => $row['pagibig_loan'] ?? 0,
            ]
        );
    }
}
