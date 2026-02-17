<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Handle the Rename safely
        if (Schema::hasColumn('employees', 'sss_loan')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->renameColumn('sss_loan', 'sss_salary_loan');
            });
        }

        // 2. Add New Columns and Modify Existing ones
        Schema::table('employees', function (Blueprint $table) {
            
            // Add new columns only if they don't exist yet
            if (!Schema::hasColumn('employees', 'sss_calamity_loan')) {
                $table->decimal('sss_calamity_loan', 10, 3)->nullable()->default(0)->after('sss_salary_loan');
            }
            
            if (!Schema::hasColumn('employees', 'premium_voluntary_ss_contribution')) {
                $table->decimal('premium_voluntary_ss_contribution', 10, 3)->nullable()->default(0)->after('sss_er');
            }

            if (!Schema::hasColumn('employees', 'pagibig_salary_loan')) {
                $table->decimal('pagibig_salary_loan', 10, 3)->nullable()->default(0)->after('pagibig_loan');
            }

            // Make existing columns nullable (Safe to run multiple times)
            $table->string('tin')->nullable()->change();
            $table->decimal('sss_ee', 10, 3)->nullable()->change();
            $table->decimal('sss_er', 10, 3)->nullable()->change();
            $table->decimal('philhealth_ee', 10, 3)->nullable()->change();
            $table->decimal('philhealth_er', 10, 3)->nullable()->change();
            $table->decimal('pagibig_ee', 10, 3)->nullable()->change();
            $table->decimal('pagibig_er', 10, 3)->nullable()->change();
            $table->decimal('pagibig_loan', 10, 3)->nullable()->change();
            
            // Ensure the renamed column is also nullable
            $table->decimal('sss_salary_loan', 10, 3)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'sss_salary_loan')) {
                $table->renameColumn('sss_salary_loan', 'sss_loan');
            }
            $table->dropColumn(['sss_calamity_loan', 'premium_voluntary_ss_contribution', 'pagibig_salary_loan']);
        });
    }
};