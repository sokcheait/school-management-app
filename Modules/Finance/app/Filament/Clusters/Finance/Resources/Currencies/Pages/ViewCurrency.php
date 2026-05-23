<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\Currencies\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Modules\Finance\Filament\Clusters\Finance\Resources\Currencies\CurrencyResource;

class ViewCurrency extends ViewRecord
{
    protected static string $resource = CurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
