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
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\Events\ActionCalling;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;
use UnitEnum;

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
        return AttendanceRecordsTable::configure($table)->headerActions([
               Action::make('import')
        ->label('Bulk Import Excel')
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
                new AttendanceRecordImport,
                $filePath
            );
        })
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendanceRecords::route('/'),
            // 'create' => CreateAttendanceRecord::route('/create'),
            // 'view' => ViewAttendanceRecord::route('/{record}'),
            //'edit' => EditAttendanceRecord::route('/{record}/edit'),
        ];
    }
}
