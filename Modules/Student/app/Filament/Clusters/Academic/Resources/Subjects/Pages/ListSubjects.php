<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Subjects\Pages;

use App\Imports\SubjectsImport;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Student\Filament\Clusters\Academic\Resources\Subjects\SubjectResource;
use Modules\Student\Models\Subject;

class ListSubjects extends ListRecords
{
    protected static string $resource = SubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncSubjectData')
                ->label('Sync Subject Data')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function () {

                    $jsonPath = module_path(
                        'Student',
                        'database/seeders/data/subjects.json'
                    );

                    if (!File::exists($jsonPath)) {

                        Notification::make()
                            ->title('Subject JSON File Not Found')
                            ->danger()
                            ->send();

                        return;
                    }

                    $subjects = json_decode(File::get($jsonPath), true);

                    foreach ($subjects as $data) {

                        Subject::updateOrCreate(
                            [
                                'code' => $data['code'], 
                            ],
                            [
                                'name_en'     => $data['name_en'],
                                'name_kh'     => $data['name_kh'],
                                'description' => $data['description'] ?? null,
                                'is_active'   => $data['is_active'] ?? true,
                            ]
                        );
                    }

                    Notification::make()
                        ->title('Subject Synchronized Successfully')
                        ->success()
                        ->send();
                }),
            CreateAction::make()
                ->label('Create Subject')
                ->icon('heroicon-o-plus'),
                
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
                Excel::import(new SubjectsImport, $data['file']);

                Notification::make()
                    ->title("successfully")
                    ->success()
                    ->send();
            })    
        ];
    }
}