<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; line-height: 1.3; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 4px; text-align: left; }
        th { background-color: #f0f0f0; }
        .section-title { background-color: #ddd; font-weight: bold; }
        .totals { font-weight: bold; background-color: #f7f7f7; }
        .net-pay { font-size: 12px; font-weight: bold; background-color: #ccc; }
        .right { text-align: right; }
    </style>
</head>
<body>

@php
    $gross_basic = $daily_rate * $days_worked;
    $absence_deduction = ($days_absent ?? 0) * $daily_rate;

       // Use the variable passed directly
    $undertime_rate_per_hour = $daily_rate / 8;
    $undertime = $undertime_hours * $undertime_rate_per_hour;

    $net_basic_pay = $gross_basic - $absence_deduction - $undertime;
    $net_holiday_pay = ($additions['holiday_ot'] ?? 0) + ($additions['other'] ?? 0);
    $gross_pay = $net_basic_pay + $net_holiday_pay;

    $net_deductions = ($deductions['sss'] ?? 0)
        + ($deductions['philhealth'] ?? 0)
        + ($deductions['pagibig'] ?? 0)
        + ($deductions['loan'] ?? 0)
        + ($deductions['shortages'] ?? 0)
        + ($deductions['advances'] ?? 0);

    $final_net_pay = $gross_pay - $net_deductions;
@endphp

<!-- HEADER -->
<table>
    <tr>
        <td colspan="2"><strong>{{ $company }}</strong><br>
        PAYROLL FOR PERIOD: {{ $payroll_period }}</td>
    </tr>
    <tr>
        <td><strong>Name:</strong> {{ $employee_name }}</td>
        <td><strong>Position:</strong> {{ $position }}</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Date:</strong> {{ $date }}</td>
    </tr>

     <tr>
        <td colspan="2"><strong>Daily Rate:</strong> PHP {{ number_format($daily_rate, 2) }}</td>
    </tr>

</table>

<!-- BASIC PAY -->
<!-- BASIC PAY -->
<!-- BASIC PAY -->
<table>
    <tr class="section-title">
        <td>Description</td>
        <td class="right">Value</td>
        <td class="right">Amount</td>
    </tr>

    <tr>
        
    
        <td>Days Worked</td>
        <td class="right">{{ number_format($days_worked,2) }} Days</td>
        <td class="right"> PHP {{ number_format($gross_basic,2) }}</td>
    </tr>

    <tr>
        <td>Days Absent</td>
        <td class="right">{{ number_format($days_absent ?? 0,2) }} Days</td>
        <td class="right"> PHP ({{ number_format($absence_deduction,2) }})</td>
    </tr>

    <tr>
        <td>Undertime Hours</td>
        <td class="right">{{ number_format($undertime_hours,2) }} hours × ({{ number_format($daily_rate,2) }} ÷ 8)</td>
        <td class="right"> PHP ({{ number_format($undertime,2) }})</td>
    </tr>

    <tr class="totals">
        <td>NET BASIC PAY</td>
        <td></td>
        <td class="right"> PHP {{ number_format($net_basic_pay,2) }}</td>
    </tr>
</table>

<!-- HOLIDAY / ADDITIONAL PAY -->
<table>
    <tr class="section-title">
        <td colspan="2">ADDITIONAL PAY</td>
    </tr>

    <tr>
        <td>Holiday / OT</td>
        <td class="right">{{ number_format($additions['holiday_ot'] ?? 0,2) }}</td>
    </tr>

    <tr>
        <td>Other Earnings</td>
        <td class="right">{{ number_format($additions['other'] ?? 0,2) }}</td>
    </tr>

    <tr class="totals">
        <td>TOTAL ADDITIONAL PAY</td>
        <td class="right"> PHP {{ number_format($net_holiday_pay,2) }}</td>
    </tr>
</table>

<!-- GROSS PAY -->
<table>
    <tr class="totals">
        <td>GROSS PAY</td>
        <td class="right"> PHP {{ number_format($gross_pay,2) }}</td>
    </tr>
</table>

<!-- DEDUCTIONS -->
<table>
    <tr class="section-title">
        <td colspan="2">DEDUCTIONS</td>
    </tr>

    <tr>
        <td>SSS</td>
        <td class="right"> PHP {{ number_format($deductions['sss'] ?? 0,2) }}</td>
    </tr>

    <tr>
        <td>PhilHealth</td>
        <td class="right"> PHP {{ number_format($deductions['philhealth'] ?? 0,2) }}</td>
    </tr>

    <tr>
        <td>Pag-IBIG</td>
        <td class="right"> PHP {{ number_format($deductions['pagibig'] ?? 0,2) }}</td>
    </tr>

    <tr>
        <td>Loans / Shortages / Cash Advance</td>
        <td class="right">
           PHP {{ number_format(
                ($deductions['loan'] ?? 0)
                + ($deductions['shortages'] ?? 0)
                + ($deductions['advances'] ?? 0),2) }}
        </td>
    </tr>

    <tr class="totals">
        <td>TOTAL DEDUCTIONS</td>
        <td class="right"> PHP {{ number_format($net_deductions,2) }}</td>
    </tr>
</table>

<<!-- NET PAY -->
<table>
    <tr class="net-pay">
        <td>NET PAY FOR THE PERIOD</td>
        <td class="right"> PHP {{ number_format($final_net_pay,2) }}</td>
    </tr>
</table>

<!-- FOOTER: Employer Signature and Date -->
<table style="margin-top:30px;">
    <tr>
        <td style="width:50%; text-align:center;">
            _______________________________<br>
            <strong>Employer's Signature</strong>
        </td>
        <td style="width:50%; text-align:center;">
            {{ now()->format('M/d/Y') }}<br>
            <strong>Date Generated</strong>
        </td>
    </tr>
</table>

</body>
</html>
