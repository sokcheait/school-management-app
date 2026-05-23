<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\StudentPayments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Modules\Finance\Models\Fee;

class StudentPaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Student Information')
                    ->schema([

                        Grid::make(2)
                            ->schema([

                                Select::make('student_id')
                                    ->label('Student')
                                    ->relationship('student', 'name_kh')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                DatePicker::make('paid_date')
                                    ->label('Payment Date')
                                    ->default(now())
                                    ->required(),

                            ]),

                    ]),

                Section::make('Payment Information')
                    ->schema([

                        Grid::make(3)
                            ->schema([

                                TextInput::make('amount')
                                    ->numeric()
                                    ->required(),

                                Select::make('currency_id')
                                    ->label('Currency')
                                    ->relationship('currency', 'code')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                            ]),

                        Grid::make(3)
                            ->schema([

                                TextInput::make('exchange_rate')
                                    ->numeric()
                                    ->default(1),

                                TextInput::make('discount')
                                    ->numeric()
                                    ->default(0),

                                TextInput::make('base_amount')
                                    ->numeric(),

                            ]),

                        Grid::make(2)
                            ->schema([

                                TextInput::make('received_amount')
                                    ->numeric()
                                    ->required(),

                                TextInput::make('change_amount')
                                    ->numeric(),

                            ]),

                        Grid::make(2)
                            ->schema([

                                Select::make('payment_method')
                                    ->options([
                                        'cash' => 'Cash',
                                        'bank' => 'Bank Transfer',
                                        'aba' => 'ABA',
                                        'acleda' => 'ACLEDA',
                                        'wing' => 'Wing',
                                    ])
                                    ->searchable()
                                    ->required(),

                                TextInput::make('receipt_no')
                                    ->maxLength(255),

                            ]),

                        Textarea::make('note')
                            ->rows(3)
                            ->columnSpanFull(),

                    ]),

            ]);
    }
}
