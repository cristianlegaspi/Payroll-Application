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
use Illuminate\Support\Facades\View;
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
            ])
            ->action(function (array $data) {

                $period = PayrollPeriod::findOrFail($data['payroll_period_id']);

                $payrolls = Payroll::with('employee')
                    ->where('payroll_period_id', $period->id)
                    ->orderBy('employee_id')
                    ->get();

                $pdf = Pdf::loadView('reports.payroll-summary', [
                    'period' => $period,
                    'payrolls' => $payrolls,
                ])->setPaper('legal', 'landscape');

                return response()->streamDownload(
                    fn () => print($pdf->output()),
                    'Payroll-Report-' . $period->description . '.pdf'
                );
            }),

                    // ✅ YOUR EXISTING GENERATE BUTTON


            Action::make('generatePayroll')
                ->label('Generate Payroll')
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
