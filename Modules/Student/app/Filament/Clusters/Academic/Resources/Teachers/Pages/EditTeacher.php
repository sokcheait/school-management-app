<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Teachers\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Student\Filament\Clusters\Academic\Resources\Teachers\TeacherResource;

class EditTeacher extends EditRecord
{
    protected static string $resource = TeacherResource::class;
}