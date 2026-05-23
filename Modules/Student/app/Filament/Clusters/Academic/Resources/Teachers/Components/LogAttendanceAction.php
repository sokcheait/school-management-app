<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Teachers\Components;

use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Notifications\Notification;
use Illuminate\Support\Arr;
use Modules\Student\Models\TeacherAttendance;
use Modules\Student\Models\TeacherTimeSlot;

class LogAttendanceAction
{
    public static function make(): Action
    {
        return Action::make('logAttendance')

            ->label('កត់វត្តមាន')
            ->icon('heroicon-o-check-circle')
            ->color('primary')
            ->modalWidth('lg')

            /*
            |--------------------------------------------------------------------------
            | DISABLE
            |--------------------------------------------------------------------------
            */
            ->disabled(function ($record) {

                $today = now()->format('l');

                $timeSlot = TeacherTimeSlot::where('teacher_id', $record->id)
                    ->whereJsonContains('day_of_week', $today)
                    ->where('is_active', true)
                    ->first();

                if (! $timeSlot) return true;

                $attendance = TeacherAttendance::where('teacher_id', $record->id)
                    ->whereDate('attendance_date', today())
                    ->first();

                $now = now();

                $checkInStart  = Carbon::today()->setTimeFromTimeString($timeSlot->check_in_start_time);
                $checkInEnd    = Carbon::today()->setTimeFromTimeString($timeSlot->check_in_end_time);
                $checkOutStart = Carbon::today()->setTimeFromTimeString($timeSlot->check_out_start_time);
                $checkOutEnd   = Carbon::today()->setTimeFromTimeString($timeSlot->check_out_end_time);

                // ❌ already fully done (check-in + check-out)
                if ($attendance && $attendance->check_in && $attendance->check_out) {
                    return true;
                }

                $inCheckInWindow  = $now->between($checkInStart, $checkInEnd);
                $inCheckOutWindow = $now->between($checkOutStart, $checkOutEnd);

                // ❌ already checked in — block button during check-in window
                if ($attendance && $attendance->check_in && $inCheckInWindow && ! $inCheckOutWindow) {
                    return true;
                }

                // ❌ not in any valid window
                if (! $inCheckInWindow && ! $inCheckOutWindow) {
                    return true;
                }

                return false;
            })

            /*
            |--------------------------------------------------------------------------
            | MOUNT
            |--------------------------------------------------------------------------
            */

            ->mountUsing(function ($form, $record) {
                $today   = now()->format('l');
                $now     = now();
                $current = $now->format('H:i');

                $timeSlot = TeacherTimeSlot::where('teacher_id', $record->id)
                    ->whereJsonContains('day_of_week', $today)
                    ->where('is_active', true)
                    ->first();

                $attendance = TeacherAttendance::where('teacher_id', $record->id)
                    ->whereDate('attendance_date', today())
                    ->first();

                /*
                |--------------------------------------------------------------------------
                | NO TIME SLOT
                |--------------------------------------------------------------------------
                */

                if (! $timeSlot) {

                    $form->fill([
                        'attendance_date'  => today()->format('Y-m-d'),
                        'status'           => 'absent',
                        'check_in'         => null,
                        'check_in_status'  => 'missing',
                        'check_out'        => null,
                        'check_out_status' => 'missing',
                    ]);

                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | TIME WINDOWS (IMPORTANT)
                |--------------------------------------------------------------------------
                */

                $checkInStart = Carbon::today()->setTimeFromTimeString($timeSlot->check_in_start_time);
                $checkInEnd   = Carbon::today()->setTimeFromTimeString($timeSlot->check_in_end_time);

                $checkOutStart = Carbon::today()->setTimeFromTimeString($timeSlot->check_out_start_time);
                $checkOutEnd   = Carbon::today()->setTimeFromTimeString($timeSlot->check_out_end_time);

                $startTime = Carbon::today()->setTimeFromTimeString($timeSlot->start_time);
                $endTime   = Carbon::today()->setTimeFromTimeString($timeSlot->end_time);

                /*
                |--------------------------------------------------------------------------
                | CASE 1: CHECK-OUT MODE (already checked in)
                |--------------------------------------------------------------------------
                */

                if (
                    $attendance &&
                    $attendance->check_in &&
                    ! $attendance->check_out &&
                    $now->between($checkOutStart, $checkOutEnd)
                ) {

                    $checkOut = $now->format('H:i');

                    $isEarlyLeave = $now->lt($endTime);

                    $form->fill([
                        'attendance_date'  => today()->format('Y-m-d'),

                        'check_in'         => null,
                        'check_in_status'  => $attendance->check_in_status ?? 'on_time',

                        'check_out'        => $checkOut,
                        'check_out_status' => $isEarlyLeave ? 'early_leave' : 'on_time',

                        'status' => $isEarlyLeave ? 'early_leave' : 'present',
                    ]);

                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | CASE 2: CHECK-IN MODE
                |--------------------------------------------------------------------------
                */

                if ($now->between($checkInStart, $checkInEnd)) {

                    // ❌ already checked in — do not allow again
                    if ($attendance && $attendance->check_in) {

                        Notification::make()
                            ->title('អ្នកបានកត់ម៉ោងចូលរួចហើយ')
                            ->warning()
                            ->send();

                        return;
                    }

                    $isLate = $now->gt($startTime);

                    $form->fill([

                        'attendance_date' => today()->format('Y-m-d'),

                        'check_in'        => $current,
                        'check_in_status' => $isLate ? 'late' : 'on_time',

                        'check_out'        => null,
                        'check_out_status' => 'missing',

                        'status' => $isLate ? 'late' : 'present',
                    ]);

                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | CASE 3: CHECKOUT WINDOW (no prior check-in)
                |--------------------------------------------------------------------------
                */

                if ($now->between($checkOutStart, $checkOutEnd)) {

                    $isEarlyLeave = $now->lt($endTime);

                    $form->fill([

                        'attendance_date' => today()->format('Y-m-d'),

                        'check_in'        => null,
                        'check_in_status' => 'missing',

                        'check_out'        => $current,
                        'check_out_status' => $isEarlyLeave ? 'early_leave' : 'on_time',

                        'status' => $attendance?->check_in
                            ? ($isEarlyLeave ? 'early_leave' : 'present')
                            : 'leave',
                    ]);

                    return;
                }
            })

            /*
            |--------------------------------------------------------------------------
            | FORM
            |--------------------------------------------------------------------------
            */

            ->form([

                DatePicker::make('attendance_date')
                    ->label('កាលបរិច្ឆេទ')
                    ->required()
                    ->native(false)
                    ->disabled()
                    ->dehydrated(true),

                Select::make('status')
                    ->label('ស្ថានភាព')
                    ->options([
                        'present'     => 'មានវត្តមាន',
                        'absent'      => 'អវត្តមាន',
                        'late'        => 'មកយឺត',
                        'leave'       => 'ច្បាប់',
                        'early_leave' => 'ចេញមុនម៉ោង',
                    ])
                    ->disabled()
                    ->dehydrated(true),

                /*
                |--------------------------------------------------------------------------
                | CHECK-IN
                |--------------------------------------------------------------------------
                */

                TimePicker::make('check_in')
                    ->label('ម៉ោងចូល')
                    ->seconds(false)
                    ->native(false)
                    ->displayFormat('H:i')
                    ->format('H:i')
                    ->live()
                    ->disabled()
                    ->dehydrated(true)

                    ->hidden(function ($record) {

                        if (! $record) return false;

                        $timeSlot = TeacherTimeSlot::where('teacher_id', $record->id)
                            ->whereJsonContains('day_of_week', now()->format('l'))
                            ->first();

                        if (! $timeSlot) return true;

                        $now = now();

                        $checkInStart = Carbon::today()->setTimeFromTimeString($timeSlot->check_in_start_time);
                        $checkInEnd   = Carbon::today()->setTimeFromTimeString($timeSlot->check_in_end_time);

                        // ❌ hide if NOT in check-in window
                        return ! $now->between($checkInStart, $checkInEnd);
                    })

                    ->afterStateUpdated(function ($state, callable $set, $record) {

                        if (! $state || ! $record) {
                            return;
                        }

                        $timeSlot = TeacherTimeSlot::where('teacher_id', $record->id)
                            ->whereJsonContains('day_of_week', now()->format('l'))
                            ->where('is_active', true)
                            ->first();

                        if (! $timeSlot) {
                            return;
                        }

                        $checkInStart = Carbon::today()
                            ->setTimeFromTimeString($timeSlot->check_in_start_time);

                        $checkInEnd = Carbon::today()
                            ->setTimeFromTimeString($timeSlot->check_in_end_time);

                        $startTime = Carbon::today()
                            ->setTimeFromTimeString($timeSlot->start_time);

                        $checkIn = Carbon::today()
                            ->setTimeFromTimeString($state);

                        /*
                        |--------------------------------------------------------------------------
                        | INVALID
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $checkIn->lt($checkInStart) ||
                            $checkIn->gt($checkInEnd)
                        ) {

                            Notification::make()
                                ->title('ម៉ោងចូលមិនត្រឹមត្រូវ')
                                ->danger()
                                ->send();

                            $set('check_in', null);
                            $set('check_in_status', 'missing');
                            $set('status', 'absent');

                            return;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | LATE
                        |--------------------------------------------------------------------------
                        */

                        $isLate = $checkIn->gt($startTime);

                        $set(
                            'check_in_status',
                            $isLate ? 'late' : 'on_time'
                        );

                        $set(
                            'status',
                            $isLate ? 'late' : 'present'
                        );
                    }),

                /*
                |--------------------------------------------------------------------------
                | CHECK-OUT
                |--------------------------------------------------------------------------
                */

                TimePicker::make('check_out')
                    ->label('ម៉ោងចេញ')
                    ->seconds(false)
                    ->native(false)
                    ->displayFormat('H:i')
                    ->format('H:i')
                    ->live()
                    ->disabled()
                    ->dehydrated(true)

                    ->hidden(function ($record) {

                        if (! $record) return true;

                        $timeSlot = TeacherTimeSlot::where('teacher_id', $record->id)
                            ->whereJsonContains('day_of_week', now()->format('l'))
                            ->first();

                        if (! $timeSlot) return true;

                        $now = now();

                        $checkOutStart = Carbon::today()->setTimeFromTimeString($timeSlot->check_out_start_time);
                        $checkOutEnd   = Carbon::today()->setTimeFromTimeString($timeSlot->check_out_end_time);

                        // ❌ hide if NOT in check-out window
                        return ! $now->between($checkOutStart, $checkOutEnd);
                    })

                    ->afterStateUpdated(function ($state, callable $set, $record) {

                        if (! $state || ! $record) {
                            return;
                        }

                        $timeSlot = TeacherTimeSlot::where('teacher_id', $record->id)
                            ->whereJsonContains('day_of_week', now()->format('l'))
                            ->where('is_active', true)
                            ->first();

                        if (! $timeSlot) {
                            return;
                        }

                        $checkOutStart = Carbon::today()
                            ->setTimeFromTimeString($timeSlot->check_out_start_time);

                        $checkOutEnd = Carbon::today()
                            ->setTimeFromTimeString($timeSlot->check_out_end_time);

                        $endTime = Carbon::today()
                            ->setTimeFromTimeString($timeSlot->end_time);

                        $checkOut = Carbon::today()
                            ->setTimeFromTimeString($state);

                        /*
                        |--------------------------------------------------------------------------
                        | INVALID
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $checkOut->lt($checkOutStart) ||
                            $checkOut->gt($checkOutEnd)
                        ) {

                            Notification::make()
                                ->title('ម៉ោងចេញមិនត្រឹមត្រូវ')
                                ->danger()
                                ->send();

                            $set('check_out', null);
                            $set('check_out_status', 'missing');

                            return;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | EARLY LEAVE
                        |--------------------------------------------------------------------------
                        */

                        $isEarlyLeave = $checkOut->lt($endTime);

                        if ($isEarlyLeave) {

                            $set('check_out_status', 'early_leave');
                            $set('status', 'early_leave');

                        } else {

                            $set('check_out_status', 'on_time');

                            $attendance = TeacherAttendance::where('teacher_id', $record->id)
                                ->whereDate('attendance_date', today())
                                ->first();

                            $set(
                                'status',
                                $attendance->status ?? 'present'
                            );
                        }
                    }),

                Select::make('check_in_status')
                    ->label('ស្ថានភាពម៉ោងចូល')
                    ->options([
                        'on_time' => 'ទាន់ម៉ោង',
                        'late'    => 'មកយឺត',
                        'missing' => 'មិនមានម៉ោងចូល',
                    ])
                    ->disabled()
                    ->dehydrated(true)
                    ->hidden(function ($record) {

                        if (! $record) return false;

                        $timeSlot = TeacherTimeSlot::where('teacher_id', $record->id)
                            ->whereJsonContains('day_of_week', now()->format('l'))
                            ->first();

                        if (! $timeSlot) return true;

                        $checkInStart = Carbon::today()->setTimeFromTimeString($timeSlot->check_in_start_time);
                        $checkInEnd   = Carbon::today()->setTimeFromTimeString($timeSlot->check_in_end_time);

                        // hide when NOT in check-in window (mirrors check_in field)
                        return ! now()->between($checkInStart, $checkInEnd);
                    })
                    ->afterStateHydrated(function ($state, callable $set, $get, $record) {

                        if (! $record) return;

                        /*
                        |--------------------------------------------------------------------------
                        | DERIVE check_in_status FROM status
                        | present  → on_time
                        | late     → late
                        | anything else → missing
                        |--------------------------------------------------------------------------
                        */

                        $status = $get('status');

                        if ($status === 'present') {
                            $set('check_in_status', 'on_time');
                            return;
                        }

                        if ($status === 'late') {
                            $set('check_in_status', 'late');
                            return;
                        }

                        // fallback: check existing attendance record
                        $attendance = TeacherAttendance::where('teacher_id', $record->id)
                            ->whereDate('attendance_date', today())
                            ->first();

                        if (! $attendance || ! $attendance->check_in) {
                            $set('check_in_status', 'missing');
                            return;
                        }

                        $slot = TeacherTimeSlot::where('teacher_id', $record->id)
                            ->whereJsonContains('day_of_week', now()->format('l'))
                            ->where('is_active', true)
                            ->first();

                        if (! $slot) return;

                        $startTime = Carbon::today()->setTimeFromTimeString($slot->start_time);
                        $check     = Carbon::today()->setTimeFromTimeString($attendance->check_in);

                        $set('check_in_status', $check->gt($startTime) ? 'late' : 'on_time');
                    }),

                Select::make('check_out_status')
                    ->label('ស្ថានភាពម៉ោងចេញ')
                    ->options([
                        'on_time'     => 'ទាន់ម៉ោង',
                        'early_leave' => 'ចេញមុនម៉ោង',
                        'missing'     => 'មិនមានម៉ោងចេញ',
                    ])
                    ->disabled()
                    ->dehydrated(true)
                    ->hidden(function ($record) {

                        if (! $record) return true;

                        $timeSlot = TeacherTimeSlot::where('teacher_id', $record->id)
                            ->whereJsonContains('day_of_week', now()->format('l'))
                            ->first();

                        if (! $timeSlot) return true;

                        $checkOutStart = Carbon::today()->setTimeFromTimeString($timeSlot->check_out_start_time);
                        $checkOutEnd   = Carbon::today()->setTimeFromTimeString($timeSlot->check_out_end_time);

                        // hide when NOT in check-out window (mirrors check_out field)
                        return ! now()->between($checkOutStart, $checkOutEnd);
                    })
                    ->afterStateHydrated(function ($state, callable $set, $record) {

                        if (! $record) return;

                        $attendance = TeacherAttendance::where('teacher_id', $record->id)
                            ->whereDate('attendance_date', today())
                            ->first();

                        if (! $attendance || ! $attendance->check_out) {
                            $set('check_out_status', 'missing');
                            return;
                        }

                        $slot = TeacherTimeSlot::where('teacher_id', $record->id)
                            ->whereJsonContains('day_of_week', now()->format('l'))
                            ->first();

                        if (! $slot) return;

                        $end   = Carbon::today()->setTimeFromTimeString($slot->end_time);
                        $check = Carbon::today()->setTimeFromTimeString($attendance->check_out);

                        $set('check_out_status', $check->lt($end) ? 'early_leave' : 'on_time');
                    }),
            ])

            /*
            |--------------------------------------------------------------------------
            | SAVE
            |--------------------------------------------------------------------------
            */

            ->action(function (array $data, $record) {

                $today = now()->format('l');

                $timeSlot = TeacherTimeSlot::where('teacher_id', $record->id)
                    ->whereJsonContains('day_of_week', $today)
                    ->where('is_active', true)
                    ->first();

                if (! $timeSlot) {
                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | WINDOWS
                |--------------------------------------------------------------------------
                */

                $checkInStart = Carbon::today()
                    ->setTimeFromTimeString($timeSlot->check_in_start_time);

                $checkInEnd = Carbon::today()
                    ->setTimeFromTimeString($timeSlot->check_in_end_time);

                $checkOutStart = Carbon::today()
                    ->setTimeFromTimeString($timeSlot->check_out_start_time);

                $checkOutEnd = Carbon::today()
                    ->setTimeFromTimeString($timeSlot->check_out_end_time);

                $startTime = Carbon::today()
                    ->setTimeFromTimeString($timeSlot->start_time);

                $endTime = Carbon::today()
                    ->setTimeFromTimeString($timeSlot->end_time);

                /*
                |--------------------------------------------------------------------------
                | CHECK-IN
                |--------------------------------------------------------------------------
                */

                if (! empty($data['check_in'])) {

                    // ❌ already checked in — reject duplicate
                    $existing = TeacherAttendance::where('teacher_id', $record->id)
                        ->whereDate('attendance_date', $data['attendance_date'])
                        ->first();

                    if ($existing && $existing->check_in) {

                        Notification::make()
                            ->title('អ្នកបានកត់ម៉ោងចូលរួចហើយ')
                            ->warning()
                            ->send();

                        return;
                    }

                    $checkIn = Carbon::today()
                        ->setTimeFromTimeString($data['check_in']);

                    // invalid window
                    if (
                        $checkIn->lt($checkInStart) ||
                        $checkIn->gt($checkInEnd)
                    ) {

                        Notification::make()
                            ->title('ម៉ោងចូលមិនស្ថិតក្នុងពេលអនុញ្ញាត')
                            ->danger()
                            ->send();

                        return;
                    }

                    $isLate = $checkIn->gt($startTime);

                    $data['check_in_status'] = $isLate
                        ? 'late'
                        : 'on_time';

                    $data['status'] = $isLate
                        ? 'late'
                        : 'present';
                }

                /*
                |--------------------------------------------------------------------------
                | CHECK-OUT
                |--------------------------------------------------------------------------
                */

                if (! empty($data['check_out'])) {

                    $checkOut = Carbon::today()
                        ->setTimeFromTimeString($data['check_out']);

                    // invalid window
                    if (
                        $checkOut->lt($checkOutStart) ||
                        $checkOut->gt($checkOutEnd)
                    ) {

                        Notification::make()
                            ->title('ម៉ោងចេញមិនស្ថិតក្នុងពេលអនុញ្ញាត')
                            ->danger()
                            ->send();

                        return;
                    }

                    $isEarlyLeave = $checkOut->lt($endTime);

                    if ($isEarlyLeave) {

                        $data['check_out_status'] = 'early_leave';
                        $data['status'] = 'early_leave';

                    } else {

                        $existing = TeacherAttendance::where(
                            'teacher_id',
                            $record->id
                        )
                            ->whereDate(
                                'attendance_date',
                                $data['attendance_date']
                            )
                            ->first();

                        $data['check_out_status'] = 'on_time';

                        $data['status'] = $existing->status
                            ?? 'present';
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | ABSENT
                |--------------------------------------------------------------------------
                */

                if (
                    empty($data['check_in']) &&
                    empty($data['check_out'])
                ) {

                    $data['status'] = 'absent';
                    $data['check_in_status'] = 'missing';
                    $data['check_out_status'] = 'missing';
                }

                /*
                |--------------------------------------------------------------------------
                | SAVE
                |--------------------------------------------------------------------------
                */

                TeacherAttendance::updateOrCreate(
                    [
                        'teacher_id'      => $record->id,
                        'attendance_date' => $data['attendance_date'],
                    ],
                    Arr::only($data, [
                        'status',
                        'check_in',
                        'check_in_status',
                        'check_out',
                        'check_out_status',
                    ])
                );

                Notification::make()
                    ->title('រក្សាទុកជោគជ័យ')
                    ->success()
                    ->send();
            });
    }
}