<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Resources\Payrolls\PayrollResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;
use App\Services\PayslipService;

class ViewPayroll extends ViewRecord
{
    protected static string $resource = PayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generatePayslip')
            ->label('Generate Payslip')
            ->icon('heroicon-o-document-text')
            ->url(fn ($record) => route('payroll.payslip', $record))
            ->openUrlInNewTab()
        ];
    }
}
