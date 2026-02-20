<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Resources\Payrolls\PayrollResource;
use App\Services\PayrollGenerator;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use App\Models\PayrollPeriod;
use App\Models\Payroll;
use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;

class ListPayrolls extends ListRecords
{
    protected static string $resource = PayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ✅ PRINT PAYROLL REPORT
            Action::make('printPayrollReport')
                ->label('Print Payroll Report')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->form([
                    // Select finalized payroll period
                    Select::make('payroll_period_id')
                        ->label('Finalized Payroll Period')
                        ->relationship(
                            name: 'payrollPeriod',
                            titleAttribute: 'description',
                            modifyQueryUsing: fn ($query) =>
                                $query->where('status', 'finalized')
                        )
                        ->searchable()
                        ->preload()
                        ->required(),

                    // Select branch (from Employee table)
                    Select::make('branch_name')
                        ->label('Branch')
                        ->options(
                            Employee::query()
                                ->select('branch_name')
                                ->distinct()
                                ->pluck('branch_name', 'branch_name')
                                ->toArray()
                        )
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data) {

                    // Ensure both period and branch are selected
                    if (empty($data['payroll_period_id']) || empty($data['branch_name'])) {
                        Notification::make()
                            ->title('Please select both payroll period and branch before printing.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $period = PayrollPeriod::findOrFail($data['payroll_period_id']);

                    $payrolls = Payroll::with('employee')
                        ->where('payroll_period_id', $period->id)
                        ->whereHas('employee', function ($query) use ($data) {
                            $query->where('branch_name', $data['branch_name']);
                        })
                        ->orderBy('employee_id')
                        ->get();

                    $pdf = Pdf::loadView('reports.payroll-summary', [
                        'period' => $period,
                        'payrolls' => $payrolls,
                        'branch' => $data['branch_name'],
                    ])->setPaper('legal', 'landscape');

                    // Stream PDF to browser for preview
                    return response()->stream(
                        fn () => print($pdf->output()),
                        200,
                        [
                            'Content-Type' => 'application/pdf',
                            'Content-Disposition' => 'inline; filename="Payroll-Report-' . $period->description . '.pdf"',
                        ]
                    );
                }),

            // ✅ GENERATE PAYROLL BUTTON
            Action::make('generatePayroll')
                ->label('Generate Payroll Report')
                ->icon('heroicon-o-currency-dollar')
                ->color('success')
                ->form([
                    Select::make('payroll_period_id')
                        ->label('Payroll Period')
                        ->relationship(
                            name: 'payrollPeriod',
                            titleAttribute: 'description',
                            modifyQueryUsing: fn ($query) =>
                                $query->where('status', 'open')
                        )
                        ->searchable()
                        ->preload()
                        ->required(),
                ])
                ->requiresConfirmation()
                ->action(function (array $data) {

                    try {
                        PayrollGenerator::generate(
                            $data['payroll_period_id']
                        );

                        Notification::make()
                            ->title('Payroll generated successfully!')
                            ->success()
                            ->send();

                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
