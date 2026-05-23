<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\TeacherTimeSlots\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TeacherTimeSlotsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('teacher.name_kh')
                    ->label('Teacher')
                    ->searchable(),

                TextColumn::make('day_of_week')
                    ->badge(),

                TextColumn::make('start_time'),

                TextColumn::make('end_time'),

                TextColumn::make('check_in_start_time'),

                TextColumn::make('check_in_end_time'),
                
                TextColumn::make('check_out_start_time'),

                TextColumn::make('check_out_end_time'),
                

                IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                //
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
