<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Teachers\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Student\Filament\Clusters\Academic\Resources\Teachers\TeacherResource;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}