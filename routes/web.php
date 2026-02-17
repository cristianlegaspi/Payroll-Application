<?php

use Illuminate\Support\Facades\Route;

use App\Models\Payroll;
use App\Services\PayslipService;
use App\Http\Controllers\EmployeePrintController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect('/admin/login');
});

Route::get('/payroll/{payroll}/payslip', function (Payroll $payroll) {
    return PayslipService::generate($payroll);
})->name('payroll.payslip');