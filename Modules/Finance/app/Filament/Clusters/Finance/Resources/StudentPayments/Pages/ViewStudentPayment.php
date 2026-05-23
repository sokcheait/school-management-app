<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\StudentPayments\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Modules\Finance\Filament\Clusters\Finance\Resources\StudentPayments\StudentPaymentResource;

class ViewStudentPayment extends ViewRecord
{
    protected static string $resource = StudentPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
