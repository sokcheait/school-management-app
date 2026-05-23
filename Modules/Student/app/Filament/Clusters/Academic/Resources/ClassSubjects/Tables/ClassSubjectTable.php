<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\ClassSubjects\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class ClassSubjectTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('schoolClass.name')
                    ->label('Class')
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Subject'),

                Tables\Columns\TextColumn::make('teacher.full_name')
                    ->label('Teacher'),

                Tables\Columns\TextColumn::make('hours_per_week')
                    ->label('Hours'),

                Tables\Columns\TextColumn::make('semester'),
            ]);
            // ->actions([
            //     EditAction::make(),
            //     DeleteAction::make(),
            // ]);
    }
}