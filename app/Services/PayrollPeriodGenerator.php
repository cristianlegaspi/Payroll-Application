<?php

namespace App\Services;

use App\Models\PayrollPeriod;
use Carbon\Carbon;

class PayrollPeriodGenerator
{
    public static function generateForYear(int $year): void
    {
        for ($month = 1; $month <= 12; $month++) {
            // First cutoff: 1–15
            $firstStart = Carbon::create($year, $month, 1);
            $firstEnd   = Carbon::create($year, $month, 15);

            PayrollPeriod::firstOrCreate(
                [
                    'start_date' => $firstStart,
                    'end_date'   => $firstEnd,
                ],
                [
                    'description' => $firstStart->format('F 1-15, Y'),
                    'status' => 'closed',
                ]
            );

            // Second cutoff: 16–end of month
            $secondStart = Carbon::create($year, $month, 16);
            $secondEnd   = Carbon::create($year, $month)->endOfMonth();

            PayrollPeriod::firstOrCreate(
                [
                    'start_date' => $secondStart,
                    'end_date'   => $secondEnd,
                ],
                [
                    'description' =>
                        $secondStart->format('F 16-') .
                        $secondEnd->format('d, Y'),
                    'status' => 'closed',
                ]
            );
        }
    }
}
