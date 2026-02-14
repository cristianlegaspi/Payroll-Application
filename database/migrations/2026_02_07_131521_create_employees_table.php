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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number')->unique();
            $table->string('full_name');
            $table->string('position');
            $table->enum('employment_status', ['Regular', 'Probationary']);
            $table->decimal('daily_rate', 10, 2);
            $table->date('date_hired');
            $table->enum('employee_type', ['Admin','Field']);
            $table->string('tin')->nullable();
            $table->decimal('sss_ee', 10, 3)->default(0); // Employee share
            $table->decimal('sss_er', 10, 3)->default(0); // Employer share
            $table->decimal('sss_loan', 10, 3)->default(0);
            $table->decimal('philhealth_ee', 10, 3)->default(0);
            $table->decimal('philhealth_er', 10, 3)->default(0);
            $table->decimal('pagibig_ee', 10, 3)->default(0);
            $table->decimal('pagibig_er', 10, 3)->default(0);
            $table->decimal('pagibig_loan', 10, 3)->default(0);

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
