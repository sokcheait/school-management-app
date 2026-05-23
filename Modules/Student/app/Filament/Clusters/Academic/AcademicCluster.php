<?php

namespace Modules\Student\Filament\Clusters\Academic;

use BackedEnum;
use Filament\Clusters\Cluster;

class AcademicCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = "hugeicons-libraries";
    
    protected static ?string $navigationLabel = 'Academic';
}
