<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\TeacherTimeSlots\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherTimeSlots\TeacherTimeSlotResource;

class ViewTeacherTimeSlot extends ViewRecord
{
    protected static string $resource = TeacherTimeSlotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
