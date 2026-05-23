<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\ClassSubjects\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClassSubjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Assign Subject to Class')
                ->columnSpanFull()
                ->schema([

                    Forms\Components\Select::make('school_class_id')
                        ->relationship('schoolClass', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),

                    Forms\Components\Select::make('subject_id')
                        ->relationship('subject', 'name_kh')
                        ->required()
                        ->searchable()
                        ->preload(),

                    Forms\Components\Select::make('teacher_id')
                        ->relationship('teacher', 'name_kh')
                        ->searchable()
                        ->preload(),

                    Forms\Components\TextInput::make('hours_per_week')
                        ->numeric()
                        ->minValue(1),

                    Forms\Components\TextInput::make('semester'),

                ])
                ->columns(2),
        ]);
    }
}