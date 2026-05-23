<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\TeacherAttendances\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherAttendances\TeacherAttendanceResource;

class EditTeacherAttendance extends EditRecord
{
    protected static string $resource = TeacherAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
