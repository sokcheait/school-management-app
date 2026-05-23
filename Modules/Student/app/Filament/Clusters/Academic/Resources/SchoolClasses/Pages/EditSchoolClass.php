<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\SchoolClasses\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Student\Filament\Clusters\Academic\Resources\SchoolClasses\SchoolClassResource;

class EditSchoolClass extends EditRecord
{
    protected static string $resource = SchoolClassResource::class;
}