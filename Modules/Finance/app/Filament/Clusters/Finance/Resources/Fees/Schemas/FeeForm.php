<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\Fees\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Fee Information')
                ->columnSpanFull()
                ->schema([

                    TextInput::make('name_en')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('name_kh')
                        ->required()
                        ->maxLength(255),    

                    TextInput::make('code')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(20),

                    TextInput::make('amount')
                        ->numeric()
                        ->required()
                        ->step(0.01),

                    Select::make('currency_id')
                        ->relationship('currency', 'code')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Toggle::make('is_active')
                        ->default(true),

                ])
                ->columns(2),
            ]);
    }
}
