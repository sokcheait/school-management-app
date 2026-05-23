<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\TeacherTimeSlots;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Modules\Student\Filament\Clusters\Academic\AcademicCluster;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherTimeSlots\Pages\CreateTeacherTimeSlot;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherTimeSlots\Pages\EditTeacherTimeSlot;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherTimeSlots\Pages\ListTeacherTimeSlots;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherTimeSlots\Pages\ViewTeacherTimeSlot;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherTimeSlots\Schemas\TeacherTimeSlotForm;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherTimeSlots\Schemas\TeacherTimeSlotInfolist;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherTimeSlots\Tables\TeacherTimeSlotsTable;
use Modules\Student\Models\TeacherTimeSlot;

class TeacherTimeSlotResource extends Resource
{
    protected static ?string $model = TeacherTimeSlot::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Teachers';

    protected static string|BackedEnum|null $navigationIcon = "hugeicons-alarm-clock";

    protected static ?string $cluster = AcademicCluster::class;

    protected static ?string $recordTitleAttribute = 'TeacherTimeSlot';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return TeacherTimeSlotForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TeacherTimeSlotInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeacherTimeSlotsTable::configure($table);
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
            'index' => ListTeacherTimeSlots::route('/'),
            'create' => CreateTeacherTimeSlot::route('/create'),
            'view' => ViewTeacherTimeSlot::route('/{record}'),
            'edit' => EditTeacherTimeSlot::route('/{record}/edit'),
        ];
    }
}
