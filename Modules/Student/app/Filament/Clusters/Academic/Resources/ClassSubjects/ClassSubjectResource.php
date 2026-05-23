<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\ClassSubjects;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Modules\Student\Filament\Clusters\Academic\AcademicCluster;
use Modules\Student\Filament\Clusters\Academic\Resources\ClassSubjects\Pages\CreateClassSubject;
use Modules\Student\Filament\Clusters\Academic\Resources\ClassSubjects\Pages\EditClassSubject;
use Modules\Student\Filament\Clusters\Academic\Resources\ClassSubjects\Pages\ListClassSubjects;
use Modules\Student\Filament\Clusters\Academic\Resources\ClassSubjects\Schemas\ClassSubjectForm;
use Modules\Student\Filament\Clusters\Academic\Resources\ClassSubjects\Tables\ClassSubjectTable;
use Modules\Student\Models\ClassSubject;

class ClassSubjectResource extends Resource
{
    protected static ?string $model = ClassSubject::class;

    protected static ?string $cluster = AcademicCluster::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Teachers';

    protected static string|BackedEnum|null $navigationIcon = 'hugeicons-job-link';

    protected static ?string $navigationLabel = 'Class Subjects';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return ClassSubjectForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClassSubjectTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClassSubjects::route('/'),
            'create' => CreateClassSubject::route('/create'),
            'edit' => EditClassSubject::route('/{record}/edit'),
        ];
    }
}