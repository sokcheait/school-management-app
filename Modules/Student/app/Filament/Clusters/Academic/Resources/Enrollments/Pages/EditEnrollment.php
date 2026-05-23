<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Enrollments\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Student\Filament\Clusters\Academic\Resources\Enrollments\EnrollmentResource;

class EditEnrollment extends EditRecord
{
    protected static string $resource = EnrollmentResource::class;
}