<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\SchoolClasses;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Modules\Student\Filament\Clusters\Academic\AcademicCluster;
use Modules\Student\Filament\Clusters\Academic\Resources\SchoolClasses\Pages\CreateSchoolClass;
use Modules\Student\Filament\Clusters\Academic\Resources\SchoolClasses\Pages\EditSchoolClass;
use Modules\Student\Filament\Clusters\Academic\Resources\SchoolClasses\Pages\ListSchoolClasses;
use Modules\Student\Filament\Clusters\Academic\Resources\SchoolClasses\Schemas\SchoolClassForm;
use Modules\Student\Filament\Clusters\Academic\Resources\SchoolClasses\Tables\SchoolClassTable;
use Modules\Student\Models\SchoolClass;

class SchoolClassResource extends Resource
{
    protected static ?string $model = SchoolClass::class;

    protected static ?string $cluster = AcademicCluster::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Students';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;


    protected static ?string $navigationLabel = 'Classes';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return SchoolClassForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SchoolClassTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSchoolClasses::route('/'),
            'create' => CreateSchoolClass::route('/create'),
            'edit' => EditSchoolClass::route('/{record}/edit'),
        ];
    }
}