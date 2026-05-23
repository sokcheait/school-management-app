<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\SchoolClasses\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Student\Filament\Clusters\Academic\Resources\SchoolClasses\SchoolClassResource;

class CreateSchoolClass extends CreateRecord
{
    protected static string $resource = SchoolClassResource::class;

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
            ->label('Save');
    }
}