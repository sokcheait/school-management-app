<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\StudentPayments;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Modules\Finance\Filament\Clusters\Finance\FinanceCluster;
use Modules\Finance\Filament\Clusters\Finance\Resources\StudentPayments\Pages\CreateStudentPayment;
use Modules\Finance\Filament\Clusters\Finance\Resources\StudentPayments\Pages\EditStudentPayment;
use Modules\Finance\Filament\Clusters\Finance\Resources\StudentPayments\Pages\ListStudentPayments;
use Modules\Finance\Filament\Clusters\Finance\Resources\StudentPayments\Pages\ViewStudentPayment;
use Modules\Finance\Filament\Clusters\Finance\Resources\StudentPayments\Schemas\StudentPaymentForm;
use Modules\Finance\Filament\Clusters\Finance\Resources\StudentPayments\Schemas\StudentPaymentInfolist;
use Modules\Finance\Filament\Clusters\Finance\Resources\StudentPayments\Tables\StudentPaymentsTable;
use Modules\Finance\Models\StudentPayment;

class StudentPaymentResource extends Resource
{
    protected static ?string $model = StudentPayment::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Payments';

    protected static string|BackedEnum|null $navigationIcon = 'hugeicons-payment-02';

    protected static ?string $cluster = FinanceCluster::class;

    protected static ?string $recordTitleAttribute = 'StudentPayment';

    public static function form(Schema $schema): Schema
    {
        return StudentPaymentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return StudentPaymentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentPaymentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudentPayments::route('/'),
            'create' => CreateStudentPayment::route('/create'),
            'view' => ViewStudentPayment::route('/{record}'),
            'edit' => EditStudentPayment::route('/{record}/edit'),
        ];
    }
}
