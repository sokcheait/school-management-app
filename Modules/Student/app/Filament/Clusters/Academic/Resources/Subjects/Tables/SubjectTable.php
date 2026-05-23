<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Subjects\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class SubjectTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_kh')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('code'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->date(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
            ]);
            // ->actions([
            //     EditAction::make(),
            //     DeleteAction::make(),
            // ])
            // ->bulkActions([
            //     Tables\Actions\DeleteBulkAction::make(),
            // ]);
    }
}