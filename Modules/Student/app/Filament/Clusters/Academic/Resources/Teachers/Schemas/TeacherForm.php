<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Teachers\Schemas;

use App\Filament\Schemas\Components\AddressInformention;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class TeacherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Teacher Info')
                ->columnSpanFull()
                ->schema([

                    SpatieMediaLibraryFileUpload::make('photo')
                            ->collection('teacher')->columnSpan(2),

                    Forms\Components\TextInput::make('name_kh')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('name_en')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('phone'),

                    Forms\Components\DatePicker::make('date_of_birth')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->placeholder('DD/MM/YYYY')
                            ->closeOnDateSelection()
                            ->prefixIcon('heroicon-o-calendar-date-range')
                            ->suffixAction(
                                Action::make('clear')
                                    ->icon('heroicon-m-x-mark')
                                    ->color('gray')
                                    ->action(fn (Set $set) => $set('date_of_birth', null))
                            )
                            ->label('Date of Birth'),

                    Forms\Components\Toggle::make('is_active')
                        ->default(true),
                    
                    ...AddressInformention::make("Address info")

                ])
                ->columns(2),
        ]);
    }
}