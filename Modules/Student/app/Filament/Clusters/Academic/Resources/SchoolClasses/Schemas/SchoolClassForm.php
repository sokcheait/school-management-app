<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\SchoolClasses\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SchoolClassForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Class Information')
                ->columnSpanFull()
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Class Name')
                        ->required(),

                    Forms\Components\TextInput::make('section')
                        ->label('Section'),

                    Forms\Components\TextInput::make('academic_year')
                        ->label('Academic Year'),
                ])
                ->columns(2),
        ]);
    }
}