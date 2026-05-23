<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\TeacherTimeSlots\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherTimeSlots\TeacherTimeSlotResource;

class EditTeacherTimeSlot extends EditRecord
{
    protected static string $resource = TeacherTimeSlotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
