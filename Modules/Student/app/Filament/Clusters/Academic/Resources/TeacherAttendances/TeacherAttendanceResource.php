<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\TeacherAttendances;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Modules\Student\Filament\Clusters\Academic\AcademicCluster;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherAttendances\Pages\CreateTeacherAttendance;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherAttendances\Pages\EditTeacherAttendance;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherAttendances\Pages\ListTeacherAttendances;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherAttendances\Pages\ViewTeacherAttendance;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherAttendances\Schemas\TeacherAttendanceForm;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherAttendances\Schemas\TeacherAttendanceInfolist;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherAttendances\Tables\TeacherAttendancesTable;
use Modules\Student\Models\TeacherAttendance;

class TeacherAttendanceResource extends Resource
{
    protected static ?string $model = TeacherAttendance::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Teachers';

    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-calendar-days";

    protected static ?string $cluster = AcademicCluster::class;

    protected static ?string $recordTitleAttribute = 'TeacherAttendance';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return TeacherAttendanceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TeacherAttendanceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeacherAttendancesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeacherAttendances::route('/'),
            'create' => CreateTeacherAttendance::route('/create'),
            'view' => ViewTeacherAttendance::route('/{record}'),
            'edit' => EditTeacherAttendance::route('/{record}/edit'),
        ];
    }
}
