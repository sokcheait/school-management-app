<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\TeacherAttendances\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherAttendances\TeacherAttendanceResource;

class ListTeacherAttendances extends ListRecords
{
    protected static string $resource = TeacherAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
