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

            Action::make('printEmployeeReport')
                ->label('Print Employee Report')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->form([

                    // ✅ Branch
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

                    // ✅ Record Status
                    Select::make('status')
                        ->label('Record Status')
                        ->options([
                            'ALL' => 'All',
                            'Active' => 'Active',
                            'Resigned' => 'Resigned',
                        ])
                        ->default('ALL')
                        ->required(),

                    // ✅ Employment Status
                    Select::make('employment_status')
                        ->label('Employment Status')
                        ->options([
                            'ALL' => 'All',
                            'Regular' => 'Regular',
                            'Probationary' => 'Probationary',
                        ])
                        ->default('ALL')
                        ->required(),
                ])
                ->action(function (array $data) {

                    $query = Employee::query();

                    // Branch filter
                    if ($data['branch_name'] !== 'ALL') {
                        $query->where('branch_name', $data['branch_name']);
                        $branchLabel = $data['branch_name'];
                    } else {
                        $branchLabel = 'All Branches';
                    }

                    // Status filter
                    if ($data['status'] !== 'ALL') {
                        $query->where('status', $data['status']);
                    }

                    // Employment Status filter
                    if ($data['employment_status'] !== 'ALL') {
                        $query->where('employment_status', $data['employment_status']);
                    }

                    $employees = $query
                        ->orderBy('branch_name')
                        ->orderBy('full_name')
                        ->get();

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
