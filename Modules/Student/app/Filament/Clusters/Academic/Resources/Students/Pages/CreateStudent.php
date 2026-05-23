<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Students\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Student\Filament\Clusters\Academic\Resources\Students\StudentResource;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(), // only "Create"
            $this->getCancelFormAction(),
        ];
    }
    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->label('Save Student');
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
