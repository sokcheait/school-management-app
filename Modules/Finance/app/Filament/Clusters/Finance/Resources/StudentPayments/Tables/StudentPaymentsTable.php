<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\StudentPayments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class StudentPaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('student.name_kh')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('amount')
                    ->money(
                        fn ($record) => $record->currency?->code ?? 'USD'
                    )
                    ->sortable(),

                TextColumn::make('currency.code')
                    ->label('Currency')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                TextColumn::make('exchange_rate')
                    ->numeric(2)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('discount')
                    ->money(
                        fn ($record) => $record->currency?->code ?? 'USD'
                    )
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('received_amount')
                    ->money(
                        fn ($record) => $record->currency?->code ?? 'USD'
                    )
                    ->sortable(),

                TextColumn::make('change_amount')
                    ->money(
                        fn ($record) => $record->currency?->code ?? 'USD'
                    )
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                BadgeColumn::make('payment_method')
                    ->colors([
                        'success' => 'cash',
                        'primary' => 'aba',
                        'warning' => 'acleda',
                        'info' => 'bank',
                        'gray' => 'wing',
                    ])
                    ->formatStateUsing(fn ($state) => strtoupper($state))
                    ->sortable(),

                TextColumn::make('receipt_no')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('paid_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([

                SelectFilter::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'bank' => 'Bank Transfer',
                        'aba' => 'ABA',
                        'acleda' => 'ACLEDA',
                        'wing' => 'Wing',
                    ]),

                SelectFilter::make('currency')
                    ->relationship('currency', 'code'),

            ])
            ->defaultSort('id', 'desc')
            ->striped()
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
