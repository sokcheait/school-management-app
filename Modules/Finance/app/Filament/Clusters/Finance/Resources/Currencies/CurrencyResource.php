<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\Currencies;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Modules\Finance\Filament\Clusters\Finance\FinanceCluster;
use Modules\Finance\Filament\Clusters\Finance\Resources\Currencies\Pages\CreateCurrency;
use Modules\Finance\Filament\Clusters\Finance\Resources\Currencies\Pages\EditCurrency;
use Modules\Finance\Filament\Clusters\Finance\Resources\Currencies\Pages\ListCurrencies;
use Modules\Finance\Filament\Clusters\Finance\Resources\Currencies\Pages\ViewCurrency;
use Modules\Finance\Filament\Clusters\Finance\Resources\Currencies\Schemas\CurrencyForm;
use Modules\Finance\Filament\Clusters\Finance\Resources\Currencies\Schemas\CurrencyInfolist;
use Modules\Finance\Filament\Clusters\Finance\Resources\Currencies\Tables\CurrenciesTable;
use Modules\Finance\Models\Currency;

class CurrencyResource extends Resource
{
    protected static ?string $model = Currency::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Payments';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $cluster = FinanceCluster::class;

    protected static ?string $recordTitleAttribute = 'Currency';

    public static function form(Schema $schema): Schema
    {
        return CurrencyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CurrencyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CurrenciesTable::configure($table);
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
            'index' => ListCurrencies::route('/'),
            'create' => CreateCurrency::route('/create'),
            'view' => ViewCurrency::route('/{record}'),
            'edit' => EditCurrency::route('/{record}/edit'),
        ];
    }
}
