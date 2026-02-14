<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payroll_period_id')->constrained()->cascadeOnDelete();

            // ======================
            // EMPLOYEE INFO SNAPSHOT
            // ======================
            $table->string('employment_status')->nullable();
            $table->string('position')->nullable();

            // ======================
            // ATTENDANCE
            // ======================
            $table->integer('days_present')->default(0);
            $table->integer('days_absent')->default(0);
            $table->decimal('undertime_hours', 8, 3)->default(0);

            // ======================
            // RATE
            // ======================
            $table->decimal('daily_rate', 10, 3)->default(0);

            // ======================
            // EARNINGS
            // ======================
            $table->decimal('basic_salary', 12, 3)->default(0);
            $table->decimal('overtime_salary', 12, 3)->default(0);
            $table->decimal('holiday_pay', 12, 3)->default(0);
            $table->decimal('other_earnings', 12, 3)->default(0);
            $table->decimal('gross_pay', 12, 3)->default(0);

            // ======================
            // MANUAL DEDUCTIONS
            // ======================
            $table->decimal('cash_advance', 12, 3)->default(0);
            $table->decimal('shortages', 12, 3)->default(0);

            // ======================
            // SSS
            // ======================
            $table->decimal('sss_er', 12, 3)->default(0);
            $table->decimal('sss_ee', 12, 3)->default(0);
            $table->decimal('sss_loan', 12, 3)->default(0);
            $table->decimal('sss_total', 12, 3)->default(0);

            // ======================
            // PHILHEALTH
            // ======================
            $table->decimal('philhealth_er', 12, 3)->default(0);
            $table->decimal('philhealth_ee', 12, 3)->default(0);

            // ======================
            // PAGIBIG
            // ======================
            $table->decimal('pagibig_er', 12, 3)->default(0);
            $table->decimal('pagibig_ee', 12, 3)->default(0);
            $table->decimal('pagibig_loan', 12, 3)->default(0);
            $table->decimal('pagibig_total', 12, 3)->default(0);

            // ======================
            // TOTAL DEDUCTION
            // ======================
            $table->decimal('total_deductions', 12, 3)->default(0);

            // ======================
            // NET PAY
            // ======================
            $table->decimal('net_pay', 12, 3)->default(0);

            // Payroll Status
            $table->string('status')->default('draft');

            // Prevent duplicate payroll per period
            $table->unique(['employee_id', 'payroll_period_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
