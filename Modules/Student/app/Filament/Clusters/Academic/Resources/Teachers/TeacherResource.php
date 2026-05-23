<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Teachers;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Modules\Student\Filament\Clusters\Academic\AcademicCluster;
use Modules\Student\Filament\Clusters\Academic\Resources\Teachers\Pages\CreateTeacher;
use Modules\Student\Filament\Clusters\Academic\Resources\Teachers\Pages\EditTeacher;
use Modules\Student\Filament\Clusters\Academic\Resources\Teachers\Pages\ListTeachers;
use Modules\Student\Filament\Clusters\Academic\Resources\Teachers\Schemas\TeacherForm;
use Modules\Student\Filament\Clusters\Academic\Resources\Teachers\Tables\TeacherTable;
use Modules\Student\Models\Teacher;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $cluster = AcademicCluster::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Teachers';

    protected static string|BackedEnum|null $navigationIcon = 'hugeicons-teacher';

    protected static ?string $navigationLabel = 'Teachers';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return TeacherForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeacherTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeachers::route('/'),
            'create' => CreateTeacher::route('/create'),
            'edit' => EditTeacher::route('/{record}/edit'),
        ];
    }
}