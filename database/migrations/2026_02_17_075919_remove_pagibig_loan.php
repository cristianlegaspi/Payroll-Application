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
        Schema::table('employees', function (Blueprint $table) {
            // 1. Remove the old pagibig_loan column safely
            if (Schema::hasColumn('employees', 'pagibig_loan')) {
                $table->dropColumn('pagibig_loan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Re-add the column if you need to rollback
            if (!Schema::hasColumn('employees', 'pagibig_loan')) {
                $table->decimal('pagibig_loan', 10, 3)->default(0)->after('pagibig_er');
            }
        });
    }
};
