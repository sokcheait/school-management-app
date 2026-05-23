<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\Currencies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CurrenciesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // Currency Code
                TextColumn::make('code')
                    ->label('Code')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                // Name
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                // Symbol
                TextColumn::make('symbol')
                    ->label('Symbol')
                    ->alignCenter(),

                // Exchange Rate
                TextColumn::make('exchange_rate')
                    ->label('Rate')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state, 4)),

                // Default Currency
                IconColumn::make('is_default')
                    ->label('Default')
                    ->boolean(),

                // Active Status
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active Status'),

                TernaryFilter::make('is_default')
                    ->label('Default Currency'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
