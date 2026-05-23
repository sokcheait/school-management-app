<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\Invoices\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Finance\Models\StudentPayment;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_no')->sortable()->searchable(),
                TextColumn::make('student.name_kh')->searchable(),
                TextColumn::make('total')->money(fn($record) => $record->currency?->code ?? 'USD'),
                TextColumn::make('balance')->money(fn($record) => $record->currency?->code ?? 'USD')->color('danger'),
                BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'unpaid',
                        'warning' => 'partial',
                        'success' => 'paid',
                    ]),
                TextColumn::make('invoice_date')->date(),
            ])
            ->filters([])
            ->actions([
                Action::make('pay')
                    ->label('Pay')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')

                    ->visible(fn ($record) => $record->status !== 'paid')

                    ->form([

                        TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->default(fn ($record) => $record->balance),

                        Select::make('payment_method')
                            ->options([
                                'cash' => 'Cash',
                                'bank' => 'Bank',
                                'aba' => 'ABA',
                            ])
                            ->required(),

                        Textarea::make('note'),
                    ])

                    ->action(function ($record, array $data) {

                        StudentPayment::create([
                            'invoice_id'      => $record->id,
                            'student_id'      => $record->student_id,
                            'currency_id'     => $record->currency_id,
                            'amount'          => $data['amount'],
                            'payment_method'  => $data['payment_method'],
                            'note'            => $data['note'] ?? null,
                            'paid_at'         => now(),
                        ]);

                        // calculate total paid
                        $paid = $record->payments()->sum('amount');

                        // update invoice
                        $record->update([
                            'paid_amount' => $paid,
                            'balance'     => $record->total - $paid,
                            'status'      => $paid >= $record->total
                                ? 'paid'
                                : 'partial',
                        ]);

                        Notification::make()
                            ->title('Payment Successful')
                            ->success()
                            ->send();
                    }),
                EditAction::make(),
                ViewAction::make(),
            ]);
    }
}
