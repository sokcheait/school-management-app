<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\StudentPayments\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Finance\Filament\Clusters\Finance\Resources\StudentPayments\StudentPaymentResource;

class CreateStudentPayment extends CreateRecord
{
    protected static string $resource = StudentPaymentResource::class;
}
