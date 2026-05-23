<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\Fees\Pages;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\File;
use Modules\Finance\Filament\Clusters\Finance\Resources\Fees\FeeResource;
use Modules\Finance\Models\Fee;

class ListFees extends ListRecords
{
    protected static string $resource = FeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncFeeData')
                ->label('Sync Fee Data')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function () {

                    // Modules/Finance/database/seeders/data/fees.json
                    $jsonPath = module_path(
                        'Finance',
                        'database/seeders/data/fees.json'
                    );

                    if (!File::exists($jsonPath)) {

                        Notification::make()
                            ->title('Fee JSON File Not Found')
                            ->danger()
                            ->send();

                        return;
                    }

                    $fees = json_decode(File::get($jsonPath), true);

                    foreach ($fees as $data) {

                        Fee::updateOrCreate(
                            [
                                'code' => $data['code'],
                            ],
                            [
                                'name_kh'      => $data['name_kh'],
                                'name_en'      => $data['name_en'],
                                'amount'       => $data['amount'],
                                'currency_id'  => $data['currency_id'],
                                'description'  => $data['description'] ?? null,
                                'is_active'    => $data['is_active'] ?? true,
                            ]
                        );
                    }

                    Notification::make()
                        ->title('Fees Synchronized Successfully')
                        ->success()
                        ->send();
                }),
            CreateAction::make(),
        ];
    }
}
