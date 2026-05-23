<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\TeacherAttendances\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherAttendances\TeacherAttendanceResource;

class ViewTeacherAttendance extends ViewRecord
{
    protected static string $resource = TeacherAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
