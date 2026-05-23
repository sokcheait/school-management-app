<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Subjects;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Modules\Student\Filament\Clusters\Academic\AcademicCluster;
use Modules\Student\Filament\Clusters\Academic\Resources\Subjects\Pages\CreateSubject;
use Modules\Student\Filament\Clusters\Academic\Resources\Subjects\Pages\EditSubject;
use Modules\Student\Filament\Clusters\Academic\Resources\Subjects\Pages\ListSubjects;
use Modules\Student\Filament\Clusters\Academic\Resources\Subjects\Schemas\SubjectForm;
use Modules\Student\Filament\Clusters\Academic\Resources\Subjects\Tables\SubjectTable;
use Modules\Student\Models\Subject;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static ?string $cluster = AcademicCluster::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Teachers';

    protected static string|BackedEnum|null $navigationIcon = 'hugeicons-bookshelf-03';

    protected static ?string $navigationLabel = 'Subjects';

    protected static ?int $navigationSort = 4;

    // 🧱 FORM
    public static function form(Schema $schema): Schema
    {
        return SubjectForm::configure($schema);
    }

    // 📊 TABLE
    public static function table(Table $table): Table
    {
        return SubjectTable::configure($table);
    }

    // 📄 PAGES
    public static function getPages(): array
    {
        return [
            'index' => ListSubjects::route('/'),
            'create' => CreateSubject::route('/create'),
            'edit' => EditSubject::route('/{record}/edit'),
        ];
    }
}