<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\ClassSubjects\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Student\Filament\Clusters\Academic\Resources\ClassSubjects\ClassSubjectResource;

class ListClassSubjects extends ListRecords
{
    protected static string $resource = ClassSubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}