<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\StudentPayments\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Finance\Filament\Clusters\Finance\Resources\StudentPayments\StudentPaymentResource;

class ListStudentPayments extends ListRecords
{
    protected static string $resource = StudentPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
