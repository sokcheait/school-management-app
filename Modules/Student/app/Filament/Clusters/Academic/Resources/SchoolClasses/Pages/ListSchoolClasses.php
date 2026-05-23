<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\SchoolClasses\Pages;

use App\Imports\SchoolClassImport;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Student\Filament\Clusters\Academic\Resources\SchoolClasses\SchoolClassResource;
use Modules\Student\Models\SchoolClass;

class ListSchoolClasses extends ListRecords
{
    protected static string $resource = SchoolClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncSchoolClass')
            ->label('Sync SchoolClass')
            ->icon('heroicon-o-arrow-path')
            ->color('primary')
            ->requiresConfirmation()
            ->modalHeading('Sync school classes Data')
            ->modalDescription('Are you sure you want to sync school classes from JSON file?')

            ->action(function () {

                $jsonPath = module_path(
                    'Student',
                    'database/seeders/data/school_classes.json'
                );

                // ❌ File check
                if (!File::exists($jsonPath)) {

                    Notification::make()
                        ->title('school_classes.json not found')
                        ->danger()
                        ->send();

                    return;
                }

                $school_classes = json_decode(File::get($jsonPath), true);

                if (!is_array($school_classes)) {

                    Notification::make()
                        ->title('Invalid JSON format')
                        ->danger()
                        ->send();

                    return;
                }

                foreach ($school_classes as $item) {
                    SchoolClass::updateOrCreate(
                        [
                            'name' => $item['name'],
                            'section' => $item['section'],
                            'academic_year' => $item['academic_year'],
                        ],
                        $item
                    );
                }
               
                Notification::make()
                    ->title("Sync Completed")
                    ->body("Created")
                    ->success()
                    ->send();
            }),

            CreateAction::make()
                ->label('Create Class')
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
                    Excel::import(new SchoolClassImport, $data['file']);

                    Notification::make()
                        ->title("successfully")
                        ->success()
                        ->send();
                }) 
        ];
    }
}