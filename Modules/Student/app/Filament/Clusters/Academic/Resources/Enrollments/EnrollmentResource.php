<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Enrollments;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Student\Filament\Clusters\Academic\AcademicCluster;
use Modules\Student\Filament\Clusters\Academic\Resources\Enrollments\Pages\CreateEnrollment;
use Modules\Student\Filament\Clusters\Academic\Resources\Enrollments\Pages\EditEnrollment;
use Modules\Student\Filament\Clusters\Academic\Resources\Enrollments\Pages\ListEnrollments;
use Modules\Student\Filament\Clusters\Academic\Resources\Enrollments\Schemas\EnrollmentForm;
use Modules\Student\Filament\Clusters\Academic\Resources\Enrollments\Tables\EnrollmentTable;
use Modules\Student\Models\Enrollment;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $cluster = AcademicCluster::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Students';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationLabel = 'Enrollments';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return EnrollmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EnrollmentTable::configure($table);
    }

    // 📄 PAGES
    public static function getPages(): array
    {
        return [
            'index' => ListEnrollments::route('/'),
            'create' => CreateEnrollment::route('/create'),
            'edit' => EditEnrollment::route('/{record}/edit'),
        ];
    }
}