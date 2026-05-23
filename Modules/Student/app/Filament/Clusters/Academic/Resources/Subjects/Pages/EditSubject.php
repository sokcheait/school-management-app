<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Subjects\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Student\Filament\Clusters\Academic\Resources\Subjects\SubjectResource;

class EditSubject extends EditRecord
{
    protected static string $resource = SubjectResource::class;
}