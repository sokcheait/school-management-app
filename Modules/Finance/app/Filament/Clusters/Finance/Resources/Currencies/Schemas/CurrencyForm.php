<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\Currencies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CurrencyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Currency Information')
                    ->columnSpanFull()
                    ->schema([

                        // Currency Code (USD, KHR, THB)
                        TextInput::make('code')
                            ->label('Currency Code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(10)
                            ->placeholder('USD'),

                        // Currency Name
                        TextInput::make('name')
                            ->label('Currency Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('US Dollar'),

                        // Symbol ($, ៛)
                        TextInput::make('symbol')
                            ->label('Symbol')
                            ->maxLength(10)
                            ->placeholder('$'),

                        // Exchange Rate
                        TextInput::make('exchange_rate')
                            ->label('Exchange Rate')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->step(0.0001)
                            ->helperText('Base currency = 1'),

                        // Default Currency
                        Toggle::make('is_default')
                            ->label('Default Currency')
                            ->helperText('Only one currency can be the base (rate = 1)')
                            ->live(),
                        // Active Status
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                    ])
                    ->columns(2),
            ]);
    }
}
