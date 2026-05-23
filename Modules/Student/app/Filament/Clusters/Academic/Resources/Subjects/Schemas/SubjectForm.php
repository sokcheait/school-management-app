<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Subjects\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SubjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Subject Info')
                ->columnSpanFull()
                ->schema([
                    Forms\Components\TextInput::make('name_kh')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('name_en')
                        ->required()
                        ->maxLength(255),    

                    Forms\Components\TextInput::make('code')
                        ->maxLength(50),

                    Forms\Components\Textarea::make('description')
                        ->columnSpanFull(),

                    Forms\Components\Toggle::make('is_active')
                        ->default(true),
                ])
                ->columns(2),
        ]);
    }
}