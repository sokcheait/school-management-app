<?php

namespace App\Filament\Schemas\Components;

use App\Models\Commune;
use App\Models\District;
use App\Models\Province;
use App\Models\Village;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;

class AddressInformention
{
    public static function make(string $label = 'addresses'): array
    {
        return [
            Section::make($label)
                ->relationship('address')
                // ->label($label)
                ->columnSpanFull()
                ->schema([
                    Select::make('province_code')
                    ->label('Province')
                    ->options(fn () =>
                       Province::pluck('province_kh', 'province_code')
                    )
                    ->live()
                    ->searchable()
                    ->required(),

                Select::make('district_code')
                    ->label('District')
                    ->options(fn (Get $get) =>
                        $get('province_code')
                            ? District::where('province_code', $get('province_code'))
                                ->pluck('district_kh', 'district_code')
                            : []
                    )
                    ->live()
                    ->searchable()
                    ->required(),

                Select::make('commune_code')
                    ->label('Commune')
                    ->options(fn (Get $get) =>
                        $get('district_code')
                            ? Commune::where('district_code', $get('district_code'))
                                ->pluck('commune_kh', 'commune_code')
                            : []
                    )
                    ->live()
                    ->searchable()
                    ->required(),

                Select::make('village_code')
                    ->label('Village')
                    ->options(fn (Get $get) =>
                        $get('commune_code')
                            ? Village::where('commune_code', $get('commune_code'))
                                ->pluck('village_kh', 'village_code')
                            : []
                    )
                    ->searchable()
                    ->required(),

                TextInput::make('house_no')->label('House No'),
                TextInput::make('street_no')->label('Street No'),
                TextInput::make('street_name')->label('Street Name'),   

                ])
                ->columns(2)
        ];
    }
}
