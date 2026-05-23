<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Students\Pages;

use App\Imports\StudentsImport;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Student\Filament\Clusters\Academic\Resources\Students\StudentResource;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
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
                    Excel::import(new StudentsImport, $data['file']);

                    Notification::make()
                        ->title("successfully")
                        ->success()
                        ->send();
                })
        ];
    }
    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Action::make('createStudent')
    //             ->label('Add Student')
    //             ->icon('heroicon-o-plus')
    //             ->url(route('filament.admin.resources.students.create')),
    //     ];
    // }
}
