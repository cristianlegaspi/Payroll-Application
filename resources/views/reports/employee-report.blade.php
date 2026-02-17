<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Employee Master List</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
        }

        .text-center {
            text-align: center;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 3px;
            text-align: center;
        }

        th {
            background: #f2f2f2;
            font-weight: bold;
        }

        .left {
            text-align: left;
        }

        .no-border {
            border: none !important;
        }
    </style>
</head>
<body>

    {{-- COMPANY HEADER --}}
    <div class="text-center mb-10">
        <h3 style="margin: 0;">E.A OCAMPO ENTERPRISES</h3>
        <small>Est. 2016</small><br>
        <strong>EMPLOYEE MASTER LIST</strong><br>
        Branch: {{ $branch }} <br>
        Generated on: {{ now()->format('F d, Y') }}
    </div>

    {{-- TABLE --}}
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Emp #</th>
                <th>Full Name</th>
                <th>Position</th>
                <th>Branch</th>
                <th>Employment Status</th>
                <th>Status</th>
                <th>Type</th>
                <th>Daily Rate</th>
                <th>Date Hired</th>
                <th>Date of Birth</th>
                <th>TIN</th>

                <th>SSS EE</th>
                <th>SSS ER</th>
                <th>SSS Premium Voluntary</th>
                <th>SSS Salary Loan</th>
                <th>SSS Calamity Loan</th>

                <th>PhilHealth EE</th>
                <th>PhilHealth ER</th>

                <th>Pag-IBIG EE</th>
                <th>Pag-IBIG ER</th>
                <th>Pag-IBIG Salary Loan</th>
            </tr>
        </thead>

        <tbody>
            @forelse($employees as $index => $employee)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $employee->employee_number }}</td>
                    <td class="left">{{ $employee->full_name }}</td>
                    <td>{{ $employee->position }}</td>
                    <td>{{ $employee->branch_name }}</td>
                    <td>{{ $employee->employment_status }}</td>
                    <td>{{ $employee->status }}</td>
                    <td>{{ $employee->employee_type }}</td>
                    <td>{{ number_format($employee->daily_rate, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($employee->date_hired)->format('M d, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($employee->date_of_birth)->format('M d, Y') }}</td>
                    <td>{{ $employee->tin }}</td>

                    <td>{{ number_format($employee->sss_ee, 2) }}</td>
                    <td>{{ number_format($employee->sss_er, 2) }}</td>
                    <td>{{ number_format($employee->premium_voluntary_ss_contribution, 2) }}</td>
                    <td>{{ number_format($employee->sss_salary_loan, 2) }}</td>
                    <td>{{ number_format($employee->sss_calamity_loan, 2) }}</td>

                    <td>{{ number_format($employee->philhealth_ee, 2) }}</td>
                    <td>{{ number_format($employee->philhealth_er, 2) }}</td>

                    <td>{{ number_format($employee->pagibig_ee, 2) }}</td>
                    <td>{{ number_format($employee->pagibig_er, 2) }}</td>
                    <td>{{ number_format($employee->pagibig_salary_loan, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="22">No employees found for this branch.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- FOOTER --}}
    <br><br>
    <table class="no-border">
        <tr class="no-border">
            <td class="no-border" style="width: 70%;">
                Total Employees: <strong>{{ $employees->count() }}</strong>
            </td>
            <td class="no-border text-center">
                ___________________________<br>
                Authorized Signature
            </td>
        </tr>
    </table>

</body>
</html>
