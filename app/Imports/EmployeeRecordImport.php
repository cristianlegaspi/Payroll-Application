<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;


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
                'branch_name'          => $row['branch_name'],
                'employment_status' => $row['employment_status'],
                'daily_rate'        => $row['daily_rate'] ?? 0,

                'date_hired' => isset($row['date_hired'])
                    ? Date::excelToDateTimeObject($row['date_hired'])
                    : null,


                'date_of_birth' => isset($row['date_hired'])
                    ? Date::excelToDateTimeObject($row['date_of_birth'])
                    : null,

                'status'        => $row['status'],
                'employee_type'     => $row['employee_type'],
                'tin'               => $row['tin'] ?? null,
                'sss_ee'            => $row['sss_ee'] ?? 0,
                'sss_er'            => $row['sss_er'] ?? 0,
                'premium_voluntary_ss_contribution'            => $row['premium_voluntary_ss_contribution'] ?? 0,
                'sss_salary_loan'   => $row['sss_salary_loan'] ?? 0,
                'sss_calamity_loan'   => $row['sss_calamity_loan'] ?? 0,


                'philhealth_ee'     => $row['philhealth_ee'] ?? 0,
                'philhealth_er'     => $row['philhealth_er'] ?? 0,
                'pagibig_ee'        => $row['pagibig_ee'] ?? 0,
                'pagibig_er'        => $row['pagibig_er'] ?? 0,
                'pagibig_salary_loan'      => $row['pagibig_salary_loan'] ?? 0,
            ]
        );
    }
}
