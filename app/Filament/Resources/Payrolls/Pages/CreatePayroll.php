<?php

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Resources\Payrolls\PayrollResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreatePayroll extends CreateRecord
{
    protected static string $resource = PayrollResource::class;


      protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    
     protected function getCreatedNotificationBody(): ?string
    {
        return 'The payroll record has been created successfully.';
    }

        protected function getCreatedNotification(): ?Notification
        {
            return Notification::make()
            ->success()
            ->title('Payroll Record Created')
            ->body($this->getCreatedNotificationBody());
        }

}
