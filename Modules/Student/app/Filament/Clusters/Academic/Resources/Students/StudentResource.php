<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Students;

use BackedEnum;
use Filament\Navigation\NavigationGroup;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\Student\Filament\Clusters\Academic\AcademicCluster;
use Modules\Student\Filament\Clusters\Academic\Resources\Students\Pages\CreateStudent;
use Modules\Student\Filament\Clusters\Academic\Resources\Students\Pages\EditStudent;
use Modules\Student\Filament\Clusters\Academic\Resources\Students\Pages\ListStudents;
use Modules\Student\Filament\Clusters\Academic\Resources\Students\Pages\ViewStudent;
use Modules\Student\Filament\Clusters\Academic\Resources\Students\Schemas\StudentForm;
use Modules\Student\Filament\Clusters\Academic\Resources\Students\Schemas\StudentInfolist;
use Modules\Student\Filament\Clusters\Academic\Resources\Students\Tables\StudentsTable;
use Modules\Student\Models\Student;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    // protected static NavigationGroup|string|null $navigationGroup = "Students";

    protected static string|\UnitEnum|null $navigationGroup = 'Students';

    protected static string|BackedEnum|null $navigationIcon = 'hugeicons-students';
    

    protected static ?string $cluster = AcademicCluster::class;

    protected static ?string $recordTitleAttribute = 'Student';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('address');
    }

    public static function form(Schema $schema): Schema
    {
        return StudentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StudentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentsTable::configure($table);
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
            'index' => ListStudents::route('/'),
            'create' => CreateStudent::route('/create'),
            'view' => ViewStudent::route('/{record}'),
            'edit' => EditStudent::route('/{record}/edit'),
        ];
    }
    // public static function mutateFormDataBeforeFill(array $data): array
    // {
    //     $address_default = [
    //         'house_no'      => '',
    //         'street_no'     => '',
    //         'street_name'   => '',
    //         'province_code' => '',
    //         'district_code' => '',
    //         'commune_code'  => '',
    //         'village_code'  => '',
    //     ];

    //     $data['addresses'] = $data['addresses'] ?? $address_default;
    //     dd($data);

    //     return $data;
    // }
}
