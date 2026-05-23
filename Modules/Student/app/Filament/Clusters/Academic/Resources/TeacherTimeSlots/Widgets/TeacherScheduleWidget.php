<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\TeacherTimeSlots\Widgets;

use Filament\Widgets\Widget;
use Modules\Student\Models\TeacherTimeSlot;

class TeacherScheduleWidget extends Widget
{
    // protected string $view = 'student::filament.widgets.teacher-schedule-widget';
    protected string $view = 'filament.widgets.teacher-schedule-widget';

    protected int|string|array $columnSpan = 'full';

    public function getViewData(): array
    {
        $events = TeacherTimeSlot::query()
            ->where('is_active', true)
            ->with('teacher')
            ->get()
            ->map(function ($slot) {

                $startDate = now()
                    ->startOfWeek()
                    ->addDays($slot->day_of_week);

                $start = $startDate->format('Y-m-d') . 'T' . $slot->start_time;

                $end = $startDate->format('Y-m-d') . 'T' . $slot->end_time;

                return [

                    'id' => $slot->id,

                    'title' => $slot->subject,

                    'start' => $start,

                    'end' => $end,

                    'backgroundColor' => match ($slot->subject) {
                        'Math' => '#2563eb',
                        'English' => '#059669',
                        'Science' => '#dc2626',
                        default => '#7c3aed',
                    },

                    'borderColor' => 'transparent',

                    'extendedProps' => [

                        'teacher' => $slot->teacher?->name,

                        'room' => $slot->room,
                    ],
                ];
            });

        return [
            'events' => $events,
        ];
    }
}