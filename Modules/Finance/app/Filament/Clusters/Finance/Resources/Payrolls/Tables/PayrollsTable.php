<?php

namespace Modules\Finance\Filament\Clusters\Finance\Resources\Payrolls\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Finance\Models\Payroll;
use Modules\Student\Models\Teacher;

class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('teacher.name_kh')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('month'),

                TextColumn::make('year'),

                TextColumn::make('base_salary')
                    ->money('USD'),

                TextColumn::make('allowance')
                    ->money('USD'),

                TextColumn::make('deduction')
                    ->money('USD'),

                TextColumn::make('net_salary')
                    ->money('USD')
                    ->weight('bold'),

                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'cancelled',
                    ]),
            ])
            ->filters([
                //
            ])
            ->headerActions([

                Action::make('generatePayroll')
                    ->label('Generate Payroll')
                    ->icon('heroicon-o-calendar')
                    ->color('success')

                    ->action(function () {

                        $teachers = Teacher::where(
                            'is_active',
                            true
                        )->get();

                        foreach ($teachers as $teacher) {

                            Payroll::firstOrCreate(
                                [
                                    'teacher_id' => $teacher->id,
                                    'month' => now()->month,
                                    'year' => now()->year,
                                ],
                                [
                                    'base_salary' => $teacher->base_salary,
                                    'allowance' => 0,
                                    'deduction' => 0,
                                    'net_salary' => $teacher->base_salary,
                                    'status' => 'pending',
                                ]
                            );
                        }

                        Notification::make()
                            ->title('Payroll Generated')
                            ->success()
                            ->send();
                    }),
            ])
            ->recordActions([
                Action::make('pay')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')

                    ->visible(
                        fn($record) =>
                        $record->status !== 'paid'
                    )

                    ->requiresConfirmation()

                    ->action(function ($record) {

                        $record->update([
                            'status' => 'paid',
                            'paid_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Salary Paid')
                            ->success()
                            ->send();
                    }),
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
