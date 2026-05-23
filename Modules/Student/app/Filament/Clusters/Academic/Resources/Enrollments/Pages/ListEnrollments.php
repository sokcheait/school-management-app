<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Enrollments\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Student\Filament\Clusters\Academic\Resources\Enrollments\EnrollmentResource;

class ListEnrollments extends ListRecords
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Enrollment')
                ->icon('heroicon-o-plus'),
        ];
    }
}