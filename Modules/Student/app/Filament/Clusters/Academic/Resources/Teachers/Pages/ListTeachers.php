<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Teachers\Pages;

use App\Imports\TeacherImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Student\Filament\Clusters\Academic\Resources\Teachers\TeacherResource;
use Modules\Student\Models\Teacher;

class ListTeachers extends ListRecords
{
    protected static string $resource = TeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncTeacherData')
            ->label('Sync Teachers')
            ->icon('heroicon-o-arrow-path')
            ->color('primary')
            ->requiresConfirmation()
            ->modalHeading('Sync Teacher Data')
            ->modalDescription('Are you sure you want to sync teachers from JSON file?')

            ->action(function () {

                $jsonPath = module_path(
                    'Student',
                    'database/seeders/data/teachers.json'
                );

                // ❌ File check
                if (!File::exists($jsonPath)) {

                    Notification::make()
                        ->title('teachers.json not found')
                        ->danger()
                        ->send();

                    return;
                }

                $teachers = json_decode(File::get($jsonPath), true);

                if (!is_array($teachers)) {

                    Notification::make()
                        ->title('Invalid JSON format')
                        ->danger()
                        ->send();

                    return;
                }

                $created = 0;
                $updated = 0;

                foreach ($teachers as $data) {

                    $teacher = Teacher::updateOrCreate(
                        [
                            'teacher_no' => $data['teacher_no'],
                        ],
                        [
                            'name_kh'       => $data['name_kh'] ?? null,
                            'name_en'       => $data['name_en'] ?? null,
                            'gender'        => $data['gender'] ?? null,
                            'email'         => $data['email'] ?? null,
                            'phone'         => $data['phone'] ?? null,
                            'date_of_birth' => $data['date_of_birth'] ?? null,
                            'base_salary'   => $data['base_salary'] ?? 0,
                            'is_active'     => $data['is_active'] ?? true,
                        ]
                    );

                    $teacher->wasRecentlyCreated ? $created++ : $updated++;
                }

                Notification::make()
                    ->title("Sync Completed")
                    ->body("Created: {$created}, Updated: {$updated}")
                    ->success()
                    ->send();
            }),
            Actions\CreateAction::make()
                ->label('Create Teacher'),
            Action::make('import')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('file')
                        ->label('Excel File')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    Excel::import(new TeacherImport, $data['file']);

                    Notification::make()
                        ->title("successfully")
                        ->success()
                        ->send();
                })    
        ];
    }
}