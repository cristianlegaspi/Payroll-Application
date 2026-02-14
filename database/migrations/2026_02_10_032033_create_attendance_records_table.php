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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('payroll_period_id')->constrained()->onDelete('cascade');
            $table->integer('days_present')->default(0);
            $table->integer('absences')->default(0);
            $table->decimal('undertime_hours', 5, 3)->default(0);
            $table->decimal('overtime_hours', 5, 3)->default(0);
            
            $table->decimal('sunday_ot_hours', 5, 3)->default(0);
            $table->decimal('sunday_days', 5, 3)->default(0);
            $table->decimal('regular_holiday_days', 5, 3)->default(0);
            $table->decimal('special_holiday_days', 5, 3)->default(0);
            
            $table->timestamps();

            $table->unique(['employee_id', 'payroll_period_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
