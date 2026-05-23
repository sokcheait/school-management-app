<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\TeacherAttendances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeacherAttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Teacher Attendance')
                    ->columnSpanFull()
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                Select::make('teacher_id')
                                    ->relationship('teacher', 'name_kh')
                                    // ->searchable()
                                    ->preload()
                                    ->required(),

                                DatePicker::make('attendance_date')
                                    ->default(now())
                                    ->required(),
                            ]),

                        Grid::make(3)
                            ->schema([

                                Select::make('status')
                                    ->options([
                                        'present' => 'Present',
                                        'absent' => 'Absent',
                                        'late' => 'Late',
                                        'leave' => 'Leave',
                                    ])
                                    ->default('present')
                                    ->required(),

                                TimePicker::make('check_in'),

                                TimePicker::make('check_out'),
                            ]),

                        Textarea::make('note')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
