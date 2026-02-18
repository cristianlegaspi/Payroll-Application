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
        Schema::table('payrolls', function (Blueprint $table) {
            // Rename existing columns
            $table->renameColumn('sss_loan', 'sss_salary_loan');
            $table->renameColumn('pagibig_loan', 'pagibig_salary_loan');

            // Add new columns
            $table->decimal('premium_voluntary_ss_contribution', 12, 3)
                  ->default(0)
                  ->after('sss_total');
            $table->decimal('sss_calamity_loan', 12, 3)
                  ->default(0)
                  ->after('sss_salary_loan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn('premium_voluntary_ss_contribution');
            $table->dropColumn('sss_calamity_loan');

            // Rename columns back
            $table->renameColumn('sss_salary_loan', 'sss_loan');
            $table->renameColumn('pagibig_salary_loan', 'pagibig_loan');
        });
    }
};
