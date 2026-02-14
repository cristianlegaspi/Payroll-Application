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
            $table->decimal('days_present', 5, 2)->default(0)->change();
            $table->decimal('days_absent', 5, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->integer('days_present')->default(0)->change();
            $table->integer('days_absent')->default(0)->change();
        });
    }
};
