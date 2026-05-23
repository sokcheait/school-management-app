<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\SchoolClasses\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class SchoolClassTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('section'),

                Tables\Columns\TextColumn::make('academic_year'),

                Tables\Columns\TextColumn::make('students_count')
                    ->counts('students')
                    ->label('Students'),
            ])
            ->filters([
                //
            ]);
    }
}