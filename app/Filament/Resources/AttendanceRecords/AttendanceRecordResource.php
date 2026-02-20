<?php

namespace App\Filament\Resources\AttendanceRecords;

use App\Filament\Resources\AttendanceRecords\Pages\CreateAttendanceRecord;
use App\Filament\Resources\AttendanceRecords\Pages\EditAttendanceRecord;
use App\Filament\Resources\AttendanceRecords\Pages\ListAttendanceRecords;
use App\Filament\Resources\AttendanceRecords\Pages\ViewAttendanceRecord;
use App\Filament\Resources\AttendanceRecords\Schemas\AttendanceRecordForm;
use App\Filament\Resources\AttendanceRecords\Schemas\AttendanceRecordInfolist;
use App\Filament\Resources\AttendanceRecords\Tables\AttendanceRecordsTable;
use App\Imports\AttendanceRecordImport;
use App\Models\AttendanceRecord;
use App\Exports\AttendanceTemplateExport;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\Events\ActionCalling;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PayrollPeriod;
use App\Models\Employee;
use UnitEnum;
use Filament\Forms\Components\Select;

class AttendanceRecordResource extends Resource
{
    protected static ?string $model = AttendanceRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;

    protected static ?string $recordTitleAttribute = 'AttendanceRecord';
    protected static ?int $navigationSort = 3;

    protected static string | UnitEnum | null $navigationGroup = 'Attendance Management';


    public static function form(Schema $schema): Schema
    {
        return AttendanceRecordForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AttendanceRecordInfolist::configure($schema);
    }

  public static function table(Table $table): Table
    {
        return AttendanceRecordsTable::configure($table)
            ->headerActions([

                // ------------------- Generate Excel Template -------------------
                Action::make('create_attendance')
                    ->label('Generate Excel File')
                    ->color('success')
                    ->form([
                        Select::make('payroll_period_id')
                            ->label('Payroll Period')
                            ->options(PayrollPeriod::where('status', 'open')->pluck('description', 'id'))
                            ->required(),

                        // Corrected branch dropdown
                        Select::make('branch_name')
                            ->label('Branch (Optional)')
                            ->options(Employee::pluck('branch_name', 'branch_name')->unique()->toArray())
                         
                            ->nullable(),
                    ])
                    ->action(function ($data) {

                        $payrollPeriod = PayrollPeriod::find($data['payroll_period_id']);

                        // Filter employees by branch_name if selected
                        $employeesQuery = Employee::query();
                        if (!empty($data['branch_name'])) {
                            $employeesQuery->where('branch_name', $data['branch_name']);
                        }
                        $employees = $employeesQuery->get();

                        $branchName = $data['branch_name'] ?? 'all_branches';
                        $filename = 'attendance_'.$payrollPeriod->description.'_'.$branchName.'.xlsx';

                        return Excel::download(
                            new AttendanceTemplateExport($employees, $payrollPeriod->description, $branchName),
                            $filename
                        );
                    }),

                // ------------------- Upload Filled Excel -------------------
                Action::make('import')
                    ->label('Upload Bulk Excel')
                    ->form([
                        FileUpload::make('file')
                            ->disk('public')
                            ->directory('imports')
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/vnd.ms-excel',
                            ])
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $filePath = storage_path('app/public/' . $data['file']);
                        Excel::import(
                            new AttendanceRecordImport(),
                            $filePath
                        );

                        \Filament\Notifications\Notification::make()
                            ->title('Attendance imported successfully!')
                            ->success()
                            ->send();
                    }),

            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendanceRecords::route('/'),
            'create' => CreateAttendanceRecord::route('/create'),
        ];
    }
}