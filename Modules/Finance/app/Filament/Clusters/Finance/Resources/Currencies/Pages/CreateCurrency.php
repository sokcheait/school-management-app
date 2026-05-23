<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\Currencies\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Finance\Filament\Clusters\Finance\Resources\Currencies\CurrencyResource;

class CreateCurrency extends CreateRecord
{
    protected static string $resource = CurrencyResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(), // only "Create"
            $this->getCancelFormAction(),
        ];
    }
    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->label('Save');
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
