<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\TeacherAttendances\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherAttendances\TeacherAttendanceResource;

class CreateTeacherAttendance extends CreateRecord
{
    protected static string $resource = TeacherAttendanceResource::class;
}
