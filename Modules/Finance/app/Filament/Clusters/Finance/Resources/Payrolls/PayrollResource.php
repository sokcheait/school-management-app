<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\Payrolls;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Modules\Finance\Filament\Clusters\Finance\FinanceCluster;
use Modules\Finance\Filament\Clusters\Finance\Resources\Payrolls\Pages\CreatePayroll;
use Modules\Finance\Filament\Clusters\Finance\Resources\Payrolls\Pages\EditPayroll;
use Modules\Finance\Filament\Clusters\Finance\Resources\Payrolls\Pages\ListPayrolls;
use Modules\Finance\Filament\Clusters\Finance\Resources\Payrolls\Pages\ViewPayroll;
use Modules\Finance\Filament\Clusters\Finance\Resources\Payrolls\Schemas\PayrollForm;
use Modules\Finance\Filament\Clusters\Finance\Resources\Payrolls\Schemas\PayrollInfolist;
use Modules\Finance\Filament\Clusters\Finance\Resources\Payrolls\Tables\PayrollsTable;
use Modules\Finance\Models\Payroll;

class PayrollResource extends Resource
{
    protected static ?string $model = Payroll::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Salary';

    protected static string|BackedEnum|null $navigationIcon ="heroicon-o-banknotes";

    protected static ?string $cluster = FinanceCluster::class;

    protected static ?string $recordTitleAttribute = 'Payroll';

    public static function form(Schema $schema): Schema
    {
        return PayrollForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PayrollInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayrollsTable::configure($table);
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
            'index' => ListPayrolls::route('/'),
            'create' => CreatePayroll::route('/create'),
            'view' => ViewPayroll::route('/{record}'),
            'edit' => EditPayroll::route('/{record}/edit'),
        ];
    }
}
