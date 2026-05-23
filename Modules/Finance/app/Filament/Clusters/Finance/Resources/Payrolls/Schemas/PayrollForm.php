<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\Payrolls\Schemas;

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
use Modules\Student\Models\Teacher;

class PayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payroll Information')
                    ->columnSpanFull()
                    ->schema([

                        Grid::make(3)
                            ->schema([

                                Select::make('teacher_id')
                                    ->relationship('teacher', 'name_kh')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (
                                        $state,
                                        Set $set
                                    ) {

                                        $teacher = Teacher::find($state);

                                        $set(
                                            'base_salary',
                                            $teacher?->base_salary ?? 0
                                        );
                                    }),

                                TextInput::make('month')
                                    ->numeric()
                                    ->default(now()->month)
                                    ->required(),

                                TextInput::make('year')
                                    ->numeric()
                                    ->default(now()->year)
                                    ->required(),
                            ]),

                        Grid::make(3)
                            ->schema([

                                TextInput::make('base_salary')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->live(),

                                TextInput::make('allowance')
                                    ->numeric()
                                    ->default(0)
                                    ->prefix('$')
                                    ->live(),

                                TextInput::make('deduction')
                                    ->numeric()
                                    ->default(0)
                                    ->prefix('$')
                                    ->live(),
                            ]),

                        TextInput::make('net_salary')
                            ->numeric()
                            ->prefix('$')
                            ->readonly()
                            ->dehydrated()
                            ->dehydrateStateUsing(
                                fn(Get $get) =>

                                (float) ($get('base_salary') ?? 0)

                                + (float) ($get('allowance') ?? 0)

                                - (float) ($get('deduction') ?? 0)
                            ),

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending'),

                        DatePicker::make('paid_at'),

                        Textarea::make('note'),

                    ]),

                Section::make('Payroll Items')
                    ->schema([

                        Repeater::make('items')
                            ->relationship('items')
                            ->schema([

                                Grid::make(3)
                                    ->schema([

                                        Select::make('type')
                                            ->options([
                                                'allowance' => 'Allowance',
                                                'deduction' => 'Deduction',
                                            ])
                                            ->required(),

                                        TextInput::make('title')
                                            ->required(),

                                        TextInput::make('amount')
                                            ->numeric()
                                            ->required()
                                            ->prefix('$'),
                                    ]),
                            ])
                            ->collapsible()
                            ->cloneable(),
                    ]),
            ]);
    }
}
