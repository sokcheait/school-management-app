<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Students\Pages;

// use Filament\Actions\DeleteAction;
// use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Student\Filament\Clusters\Academic\Resources\Students\StudentResource;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ViewAction::make(),
            // DeleteAction::make(),
        ];
    }
    
}
