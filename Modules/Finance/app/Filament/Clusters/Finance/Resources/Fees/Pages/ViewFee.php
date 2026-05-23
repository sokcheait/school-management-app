<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\Fees\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Modules\Finance\Filament\Clusters\Finance\Resources\Fees\FeeResource;

class ViewFee extends ViewRecord
{
    protected static string $resource = FeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
