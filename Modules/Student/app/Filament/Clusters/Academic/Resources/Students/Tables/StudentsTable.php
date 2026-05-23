<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Students\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\URL;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
         return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('photo')
                    ->collection('students')
                    // ->circular()
                    ->size(60)
                    ->defaultImageUrl(fn()=> URL::asset('images/default.png')),

                TextColumn::make('name_kh')
                    ->label('Name KH')
                    ->searchable(),
                TextColumn::make('name_en')
                    ->label('Name EN')
                    ->searchable(),

                TextColumn::make('gender')
                    ->label('Gender')
                    ->badge(),         

                TextColumn::make('email'),
                TextColumn::make('phone'),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active Status'),
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
