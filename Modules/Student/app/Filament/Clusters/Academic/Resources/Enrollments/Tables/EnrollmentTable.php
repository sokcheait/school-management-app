<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Enrollments\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class EnrollmentTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.name_kh')
                    ->label('Student')
                    ->searchable(),

                Tables\Columns\TextColumn::make('schoolClass.name')
                    ->label('Class'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => $state === 'active' ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('enrolled_at')
                    ->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
            ]);
    }
}