<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\TeacherTimeSlots\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Modules\Student\Models\Teacher;

class TeacherTimeSlotForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Teacher TimeSlot Info')
                    ->columnSpanFull()
                    ->schema([

                        // Teacher
                        Select::make('teacher_id')
                            ->label('Teacher')
                            ->options(
                                Teacher::query()
                                    ->pluck('name_kh', 'id')
                            )
                            ->disabled()
                            ->searchable()
                            ->required(),

                        // Day of Week
                        Select::make('day_of_week')
                            ->label('Day of Week')
                            ->multiple()
                            ->options([
                                'Monday' => 'Monday',
                                'Tuesday' => 'Tuesday',
                                'Wednesday' => 'Wednesday',
                                'Thursday' => 'Thursday',
                                'Friday' => 'Friday',
                                'Saturday' => 'Saturday',
                                'Sunday' => 'Sunday',
                            ])
                            ->required(),

                        // Start Time
                        TimePicker::make('start_time')
                            ->label('Start Time')
                            ->seconds(false)
                            ->native(false)
                            ->displayFormat('H:i')
                            ->format('H:i')
                            ->required(),

                        // End Time
                        TimePicker::make('end_time')
                            ->label('End Time')
                            ->seconds(false)
                             ->native(false)
                            ->displayFormat('H:i')
                            ->format('H:i')
                            ->required(),
                        
                        TimePicker::make('check_in_start_time')
                        ->label('Check-in Start Time')
                        ->seconds(false)
                        ->native(false)
                        ->displayFormat('H:i')
                        ->format('H:i')
                        ->required(),

                        TimePicker::make('check_in_end_time')
                            ->label('Check-in End Time')
                            ->seconds(false)
                            ->native(false)
                            ->displayFormat('H:i')
                            ->format('H:i')
                            ->required(),


                        TimePicker::make('check_out_start_time')
                            ->label('Check-out Start Time')
                            ->seconds(false)
                            ->native(false)
                            ->displayFormat('H:i')
                            ->format('H:i')
                            ->required(),

                        TimePicker::make('check_out_end_time')
                            ->label('Check-out End Time')
                            ->seconds(false)
                            ->native(false)
                            ->displayFormat('H:i')
                            ->format('H:i')
                            ->required(),   

                        // Subject
                        TextInput::make('subject')
                            ->label('Subject')
                            ->maxLength(255),

                        // Room
                        TextInput::make('room')
                            ->label('Room')
                            ->maxLength(255),

                        // Active
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                    ])->columns(2),
            ]);
    }
}
