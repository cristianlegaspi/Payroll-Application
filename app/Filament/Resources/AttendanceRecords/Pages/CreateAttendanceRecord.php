<?php

namespace App\Filament\Resources\AttendanceRecords\Pages;

use App\Filament\Resources\AttendanceRecords\AttendanceRecordResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateAttendanceRecord extends CreateRecord
{
    protected static string $resource = AttendanceRecordResource::class;

     protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

     protected function getCreatedNotificationBody(): ?string
    {
        return 'The attendance record has been created successfully.';
    }

     protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
        ->success()
        ->title('Attendance Record Created')
        ->body($this->getCreatedNotificationBody());
    }
    
}
