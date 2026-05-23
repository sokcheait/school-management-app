<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\Fees;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Modules\Finance\Filament\Clusters\Finance\FinanceCluster;
use Modules\Finance\Filament\Clusters\Finance\Resources\Fees\Pages\CreateFee;
use Modules\Finance\Filament\Clusters\Finance\Resources\Fees\Pages\EditFee;
use Modules\Finance\Filament\Clusters\Finance\Resources\Fees\Pages\ListFees;
use Modules\Finance\Filament\Clusters\Finance\Resources\Fees\Pages\ViewFee;
use Modules\Finance\Filament\Clusters\Finance\Resources\Fees\Schemas\FeeForm;
use Modules\Finance\Filament\Clusters\Finance\Resources\Fees\Schemas\FeeInfolist;
use Modules\Finance\Filament\Clusters\Finance\Resources\Fees\Tables\FeesTable;
use Modules\Finance\Models\Fee;

class FeeResource extends Resource
{
    protected static ?string $model = Fee::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Payments';

    protected static string|BackedEnum|null $navigationIcon = 'hugeicons-tags';

    protected static ?string $cluster = FinanceCluster::class;

    protected static ?string $recordTitleAttribute = 'Fee';

    public static function form(Schema $schema): Schema
    {
        return FeeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FeeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeesTable::configure($table);
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
            'index' => ListFees::route('/'),
            'create' => CreateFee::route('/create'),
            'view' => ViewFee::route('/{record}'),
            'edit' => EditFee::route('/{record}/edit'),
        ];
    }
}
