<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\TeacherTimeSlots\Pages;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\File;
use Modules\Student\Filament\Clusters\Academic\Resources\TeacherTimeSlots\TeacherTimeSlotResource;
use Modules\Student\Models\Teacher;
use Modules\Student\Models\TeacherTimeSlot;

class ListTeacherTimeSlots extends ListRecords
{
    protected static string $resource = TeacherTimeSlotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Action::make('syncTeacherTimeSlotData')
            // ->label('Sync Teacher Time Slots')
            // ->icon('heroicon-o-arrow-path')
            // ->color('warning')
            // ->requiresConfirmation()

            // ->action(function () {

            //     $jsonPath = module_path(
            //         'Student',
            //         'database/seeders/data/teacher_time_slots.json'
            //     );

            //     // ❌ File check
            //     if (!File::exists($jsonPath)) {

            //         Notification::make()
            //             ->title('Teacher TimeSlot JSON File Not Found')
            //             ->danger()
            //             ->send();

            //         return;
            //     }

            //     // 📦 Decode JSON
            //     $slots = json_decode(File::get($jsonPath), true);

            //     if (!is_array($slots)) {

            //         Notification::make()
            //             ->title('Invalid JSON Format')
            //             ->danger()
            //             ->send();

            //         return;
            //     }

            //     $count = 0;
            //     $skipped = 0;

            //     foreach ($slots as $data) {

            //         $teacher = Teacher::where('teacher_no', $data['teacher_no'] ?? null)
            //             ->first();

            //         if (! $teacher) {
            //             $skipped++;
            //             continue;
            //         }

            //         TeacherTimeSlot::updateOrCreate(
            //             [
            //                 'teacher_id'  => $teacher->id,
            //                 'day_of_week' => $data['day_of_week'],
            //                 'start_time'  => $data['start_time'],
            //             ],
            //             [
            //                 'end_time'  => $data['end_time'],
            //                 'subject'   => $data['subject'] ?? null,
            //                 'room'      => $data['room'] ?? null,
            //                 'is_active' => $data['is_active'] ?? true,
            //             ]
            //         );

            //         $count++;
            //     }

            //     Notification::make()
            //         ->title("Synced: {$count} | Skipped: {$skipped}")
            //         ->success()
            //         ->send();
            // }),
            
            // CreateAction::make(),
        ];
    }
}
