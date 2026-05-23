<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Subjects\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Student\Filament\Clusters\Academic\Resources\Subjects\SubjectResource;

class CreateSubject extends CreateRecord
{
    protected static string $resource = SubjectResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}