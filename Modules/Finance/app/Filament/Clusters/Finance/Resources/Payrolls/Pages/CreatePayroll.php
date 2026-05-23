<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\Payrolls\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Finance\Filament\Clusters\Finance\Resources\Payrolls\PayrollResource;

class CreatePayroll extends CreateRecord
{
    protected static string $resource = PayrollResource::class;
}
