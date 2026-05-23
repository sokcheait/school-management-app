<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Teachers\Components;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Modules\Student\Models\TeacherTimeSlot;

class CreateTimeSloteAction
{
    public static function make(): Action
    {
        return Action::make('create_time_slote')

            ->label('Create Time Slot')
            ->icon('coolicon-timer-add')
            ->color('primary')
            ->modalWidth('2xl')

            /*
            |--------------------------------------------------------------------------
            | FORM
            |--------------------------------------------------------------------------
            */

            ->schema([
                Section::make('')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    Select::make('day_of_week')
                        ->label('Day of Week')
                        ->multiple()
                        ->required()
                        ->options([
                            'Monday'    => 'Monday',
                            'Tuesday'   => 'Tuesday',
                            'Wednesday' => 'Wednesday',
                            'Thursday'  => 'Thursday',
                            'Friday'    => 'Friday',
                            'Saturday'  => 'Saturday',
                            'Sunday'    => 'Sunday',
                        ])->columnSpan(2),

                    TimePicker::make('start_time')
                        ->label('Start Time')
                        ->seconds(false)
                        ->native(false)
                        ->displayFormat('H:i')
                        ->format('H:i')
                        ->required()
                        ->default('08:00'),

                    TimePicker::make('end_time')
                        ->label('End Time')
                        ->seconds(false)
                        ->native(false)
                        ->displayFormat('H:i')
                        ->format('H:i')
                        ->required()
                        ->default('17:00'),

                    TimePicker::make('check_in_start_time')
                        ->label('Check-in Start Time')
                        ->helperText('Earliest allowed check-in')
                        ->seconds(false)
                        ->native(false)
                        ->displayFormat('H:i')
                        ->format('H:i')
                        ->required()
                        ->default('07:30'),

                    TimePicker::make('check_in_end_time')
                        ->label('Check-in End Time')
                        ->helperText('Latest allowed check-in')
                        ->seconds(false)
                        ->native(false)
                        ->displayFormat('H:i')
                        ->format('H:i')
                        ->required()
                        ->default('08:30'),

                    TimePicker::make('check_out_start_time')
                        ->label('Check-out Start Time')
                        ->helperText('Earliest allowed check-out')
                        ->seconds(false)
                        ->native(false)
                        ->displayFormat('H:i')
                        ->format('H:i')
                        ->required()
                        ->default('16:30'),

                    TimePicker::make('check_out_end_time')
                        ->label('Check-out End Time')
                        ->helperText('Latest allowed check-out')
                        ->seconds(false)
                        ->native(false)
                        ->displayFormat('H:i')
                        ->format('H:i')
                        ->required()
                        ->default('18:00'),
                ]),
            ])

            /*
            |--------------------------------------------------------------------------
            | ACTION
            |--------------------------------------------------------------------------
            */

            ->action(function (array $data, $record) {

                /*
                |--------------------------------------------------------------------------
                | DUPLICATE CHECK — one teacher = one time slot only
                |--------------------------------------------------------------------------
                */
 
                $exists = TeacherTimeSlot::where('teacher_id', $record->id)
                    ->where('is_active', true)
                    ->exists();
 
                if ($exists) {
 
                    Notification::make()
                        ->title('Duplicate TimeSlot')
                        ->body('This teacher already has an active time slot.')
                        ->danger()
                        ->send();
 
                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | CREATE
                |--------------------------------------------------------------------------
                */

                TeacherTimeSlot::create([

                    'teacher_id' => $record->id,

                    /*
                    |--------------------------------------------------------------------------
                    | DAY
                    |--------------------------------------------------------------------------
                    */

                    'day_of_week' => $data['day_of_week'],

                    /*
                    |--------------------------------------------------------------------------
                    | WORKING TIME
                    |--------------------------------------------------------------------------
                    */

                    'start_time' => $data['start_time'],
                    'end_time'   => $data['end_time'],

                    /*
                    |--------------------------------------------------------------------------
                    | CHECK-IN
                    |--------------------------------------------------------------------------
                    */

                    'check_in_start_time' => $data['check_in_start_time'],
                    'check_in_end_time'   => $data['check_in_end_time'],

                    /*
                    |--------------------------------------------------------------------------
                    | CHECK-OUT
                    |--------------------------------------------------------------------------
                    */

                    'check_out_start_time' => $data['check_out_start_time'],
                    'check_out_end_time'   => $data['check_out_end_time'],

                    /*
                    |--------------------------------------------------------------------------
                    | STATUS
                    |--------------------------------------------------------------------------
                    */

                    'is_active' => true,
                ]);

                Notification::make()
                    ->title(
                        'Time slot created for '
                        . count($data['day_of_week'])
                        . ' day(s)'
                    )
                    ->success()
                    ->send();
            });
    }
}