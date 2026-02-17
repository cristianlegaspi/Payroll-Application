<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [

            // ✅ PRINT EMPLOYEE REPORT
            Action::make('printEmployeeReport')
                ->label('Print Employee Report')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->form([
                    Select::make('branch_name')
                        ->label('Branch')
                        ->options(
                            ['ALL' => 'All Branches'] + 
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

                    // If ALL selected, get all employees
                    if ($data['branch_name'] === 'ALL') {
                        $employees = Employee::orderBy('branch_name')
                            ->orderBy('full_name')
                            ->get();

                        $branchLabel = 'All Branches';
                    } else {
                        $employees = Employee::where('branch_name', $data['branch_name'])
                            ->orderBy('full_name')
                            ->get();

                        $branchLabel = $data['branch_name'];
                    }

                    $pdf = Pdf::loadView('reports.employee-report', [
                        'employees' => $employees,
                        'branch' => $branchLabel,
                    ])->setPaper('legal', 'landscape');

                    return response()->stream(
                        fn () => print($pdf->output()),
                        200,
                        [
                            'Content-Type' => 'application/pdf',
                            'Content-Disposition' => 'inline; filename="Employee-Report.pdf"',
                        ]
                    );
                }),

            CreateAction::make(),
        ];
    }
}
