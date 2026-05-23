<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\ClassSubjects\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Student\Filament\Clusters\Academic\Resources\ClassSubjects\ClassSubjectResource;

class EditClassSubject extends EditRecord
{
    protected static string $resource = ClassSubjectResource::class;
}