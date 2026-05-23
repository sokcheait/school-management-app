<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\Invoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Modules\Finance\Models\Fee;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->columnSpanFull()
                    ->schema([
                        // Left Column: Main Details
                        Section::make('Invoice Information')
                            ->columnSpan(2)
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('invoice_no')
                                        ->default('INV-' . date('YmdHis'))
                                        ->required()
                                        // ->disabled()
                                        ->unique(ignoreRecord: true),
                                    Select::make('student_id')
                                        ->label('Student')
                                        ->relationship(
                                            name: 'student',
                                            titleAttribute: 'name_kh',
                                        )
                                        // ->searchable()
                                        ->preload(false),
                                    Select::make('currency_id')
                                        ->relationship('currency', 'code')
                                        ->default(1)
                                        ->required(),
                                    Select::make('status')
                                        ->options([
                                            'unpaid' => 'Unpaid',
                                            'partial' => 'Partial',
                                            'paid' => 'Paid',
                                        ])->default('unpaid'),
                                    DatePicker::make('invoice_date')->default(now()),
                                    DatePicker::make('due_date')->default(now()->addDays(30)),
                                ]),

                                Repeater::make('items')
                                    ->relationship('items')
                                    ->schema([
                                        Select::make('fee_id')
                                            ->relationship('fee', 'name_kh')
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, Set $set,Get $get) {
                                                $fee = Fee::find($state);
                                                $set('price', $fee?->amount ?? 0);
                                            })->columnSpan(2),
                                        TextInput::make('qty')
                                            ->numeric()
                                            ->default(1)
                                            ->live()
                                            ->columnSpan(1),
                                        TextInput::make('price')
                                            ->numeric()
                                            ->prefix('$')
                                            ->live()
                                            ->columnSpan(1),
                                        TextInput::make('amount')
                                            ->numeric()
                                            ->placeholder(fn ($get) => $get('qty') * $get('price'))
                                            ->dehydrated()
                                            ->readonly()
                                            ->dehydrateStateUsing(function (Get $get) {
                                                return (float) ($get('qty') ?? 0) * (float) ($get('price') ?? 0);
                                            })
                                            ->columnSpan(1),   
                                    ])
                                    ->columns(5)
                                    ->live()
                                    ->afterStateUpdated(fn ($get, $set) => self::calculateTotals($get, $set)),
                            ]),

                        // Right Column: Totals & Notes
                        Section::make('Summary')
                            ->columnSpan(1)
                            ->schema([
                                TextInput::make('subtotal')
                                    ->numeric()
                                    ->readonly()
                                    ->extraInputAttributes(['class' => 'text-right']),
                                TextInput::make('discount')
                                    ->numeric()
                                    ->default(0)
                                    ->live()
                                    ->afterStateUpdated(fn ($get, $set) => self::calculateTotals($get, $set)),
                                TextInput::make('total')
                                    ->numeric()
                                    ->readonly()
                                    ->label('Grand Total')
                                    ->extraInputAttributes(['class' => 'text-right font-bold text-primary-600']),
                                Textarea::make('note')->rows(3),
                            ]),
                    ]),
            ]);
    }
    public static function calculateTotals(Get $get, Set $set)
    {
        // Calculate Subtotal from Repeater
        $subtotal = collect($get('items'))->reduce(function ($carry, $item) {
            return $carry + ($item['qty'] * $item['price']);
        }, 0);

        $discount = (float) $get('discount') ?? 0;
        
        $set('subtotal', number_format($subtotal, 2, '.', ''));
        $set('total', number_format($subtotal - $discount, 2, '.', ''));
        $set('balance', number_format($subtotal - $discount - ($get('paid_amount') ?? 0), 2, '.', ''));
    }
}
