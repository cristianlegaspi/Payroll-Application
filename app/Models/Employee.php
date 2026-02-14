<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    // Table name (optional if standard)
    protected $table = 'employees';

    // Mass assignable fields
    protected $fillable = [
        'employee_number',
        'full_name',
        'position',
        'employment_status',
        'daily_rate',
        'date_hired',
        'employee_type',
        'tin',
        'sss_ee',
        'sss_er',
        'sss_loan',
        'philhealth_ee',
        'philhealth_er',
        'pagibig_ee',
        'pagibig_er',
        'pagibig_loan',     
    ];

    // Casts for specific data types
    protected $casts = [
        'daily_rate' => 'decimal:2',
        'date_hired' => 'date',
    ];

    // Optionally: custom accessor for full display
    public function getDisplayNameAttribute()
    {
        return "{$this->employee_number} - {$this->full_name}";
    }

      public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

  
}
