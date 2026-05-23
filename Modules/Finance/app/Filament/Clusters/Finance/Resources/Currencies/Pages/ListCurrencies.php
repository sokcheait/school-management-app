<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\Currencies\Pages;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\File;
use Modules\Finance\Filament\Clusters\Finance\Resources\Currencies\CurrencyResource;
use Modules\Finance\Models\Currency;

class ListCurrencies extends ListRecords
{
    protected static string $resource = CurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncData')
                ->label('Sync Currency Data')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function () {
                    // 1. Get the JSON data
                    // Modules/Finance/database/seeders/data/currencies.json
                    $jsonPath = module_path('Finance', 'database/seeders/data/currencies.json');;
                    // dd($jsonPath);
                    
                    if (!File::exists($jsonPath)) {
                        Notification::make()
                            ->title('JSON File Not Found')
                            ->danger()
                            ->send();
                        return;
                    }

                    $currencies = json_decode(File::get($jsonPath), true);

                    // 2. Perform the update/create logic
                    foreach ($currencies as $data) {
                        Currency::updateOrCreate(
                            ['code' => $data['code']],
                            $data
                        );
                    }

                    // 3. Send success notification
                    Notification::make()
                        ->title('Currencies Synchronized')
                        ->success()
                        ->send();
                }),
            CreateAction::make(),
        ];
    }
}
