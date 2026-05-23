<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\TeacherTimeSlots\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherTimeSlots\TeacherTimeSlotResource;

class CreateTeacherTimeSlot extends CreateRecord
{
    protected static string $resource = TeacherTimeSlotResource::class;
}
