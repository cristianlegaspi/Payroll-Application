<?php

namespace App\Filament\Resources\AttendanceRecords\Pages;

use App\Filament\Resources\AttendanceRecords\AttendanceRecordResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditAttendanceRecord extends EditRecord
{
    protected static string $resource = AttendanceRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ViewAction::make(),
            // DeleteAction::make(),
        ];
    }

      protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
    
     protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
        ->success()
        ->title('Attendance Record Updated')
        ->body('The attendance record has been updated successfully');
    }
}
