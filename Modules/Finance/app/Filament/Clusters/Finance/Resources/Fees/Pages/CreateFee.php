<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\Fees\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Finance\Filament\Clusters\Finance\Resources\Fees\FeeResource;

class CreateFee extends CreateRecord
{
    protected static string $resource = FeeResource::class;

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
