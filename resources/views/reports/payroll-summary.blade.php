<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Payroll Summary</title>

<style>

body {
    font-family: Arial, sans-serif;
    font-size: 9px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid black;
    padding: 4px;
    text-align: right;
}

th {
    font-weight: bold;
    text-align: center;
}

.text-left {
    text-align: left;
}

.bold {
    font-weight: bold;
}

.signature {
    height: 20px;
}

</style>

</head>
<body>


<h3 style="text-align:center; margin-bottom:0;">
FULLTANK GASOLINE STATION
</h3>

<p style="text-align:center; margin-top:2px;">
Payroll Summary - {{ $period->description }}
</p>


@php

$columns = [

'daily_rate',
'basic_salary',
'overtime_salary',
'holiday_pay',
'gross_pay',
'cash_advance',
'sss_er',
'sss_ee',
'sss_loan',
'philhealth_er',
'philhealth_ee',
'pagibig_er',
'pagibig_ee',
'pagibig_loan',
'total_deductions',
'net_pay'

];

$grand = [];
$admin = [];
$field = [];

foreach ($columns as $col){

$grand[$col] = 0;
$admin[$col] = 0;
$field[$col] = 0;

}

@endphp


<table>

<tr>

<th>Name of Employee</th>
<th>Employment Status</th>
<th>Position</th>

<th>Daily Rate</th>
<th>Basic Salary</th>
<th>Overtime</th>
<th>Holiday Pay</th>
<th>Gross Pay</th>
<th>Cash Advance</th>

<th>SSS ER</th>
<th>SSS EE</th>
<th>SSS Loan</th>

<th>PhilHealth ER</th>
<th>PhilHealth EE</th>

<th>Pagibig ER</th>
<th>Pagibig EE</th>
<th>Pagibig Loan</th>

<th>Total Deduction</th>
<th>Net Pay</th>

<th>Signature</th>

</tr>


@foreach($payrolls as $payroll)

@php

$type = $payroll->employee->employee_type ?? 'Field';

foreach ($columns as $col){

$value = $payroll->$col ?? 0;

$grand[$col] += $value;

if($type == 'Admin'){

$admin[$col] += $value;

}else{

$field[$col] += $value;

}

}

@endphp


<tr>

<td class="text-left">
{{ $payroll->employee->full_name }}
</td>

<td class="text-left">
{{ $payroll->employment_status }}
</td>

<td class="text-left">
{{ $payroll->position }}
</td>

<td>{{ number_format($payroll->daily_rate,2) }}</td>
<td>{{ number_format($payroll->basic_salary,2) }}</td>
<td>{{ number_format($payroll->overtime_salary,2) }}</td>
<td>{{ number_format($payroll->holiday_pay,2) }}</td>
<td>{{ number_format($payroll->gross_pay,2) }}</td>
<td>{{ number_format($payroll->cash_advance,2) }}</td>

<td>{{ number_format($payroll->sss_er,2) }}</td>
<td>{{ number_format($payroll->sss_ee,2) }}</td>
<td>{{ number_format($payroll->sss_loan,2) }}</td>

<td>{{ number_format($payroll->philhealth_er,2) }}</td>
<td>{{ number_format($payroll->philhealth_ee,2) }}</td>

<td>{{ number_format($payroll->pagibig_er,2) }}</td>
<td>{{ number_format($payroll->pagibig_ee,2) }}</td>
<td>{{ number_format($payroll->pagibig_loan,2) }}</td>

<td>{{ number_format($payroll->total_deductions,2) }}</td>

<td class="bold">
{{ number_format($payroll->net_pay,2) }}
</td>

<td class="signature"></td>

</tr>


@endforeach




<tr>



<td class="text-left bold">
TOTAL ADMIN
</td>

<td colspan="2"></td>

@foreach($columns as $col)

<td class="bold">
{{ number_format($admin[$col],2) }}
</td>

@endforeach

<td></td>

</tr>



<tr>

<td class="text-left bold">
TOTAL FIELD
</td>

<td colspan="2"></td>

@foreach($columns as $col)

<td class="bold">
{{ number_format($field[$col],2) }}
</td>

@endforeach

<td></td>

</tr>



<tr>

<td class="text-left bold">
TOTAL COMPANY
</td>

<td colspan="2"></td>

@foreach($columns as $col)

<td class="bold">
{{ number_format($grand[$col],2) }}
</td>

@endforeach

<td></td>

</tr>


</table>



<br><br><br>


<table style="width:100%; border:0;">

<tr style="border:0;">


<td style="border:0; text-align:left;">

Prepared by:<br><br><br>


_________________________<br>

Name and Signature


</td>



<td style="border:0; text-align:left;">


Approved by:<br><br><br>


<strong>

HAZEL DE LEON OCAMPO

</strong><br>

Authorized Signatory


</td>


</tr>

</table>



</body>
</html>
