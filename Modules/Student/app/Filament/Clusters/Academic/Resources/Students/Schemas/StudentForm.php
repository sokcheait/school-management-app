<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Students\Schemas;

use App\Filament\Schemas\Components\AddressInformention;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Student Information')
                    ->columnSpanFull()
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('photo')
                            ->collection('students')->columnSpan(2),

                        Forms\Components\TextInput::make('name_kh')
                            ->label('Name KH')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('name_en')
                            ->label('Name EN')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(20),

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

                        // Forms\Components\Textarea::make('address')
                        //     ->rows(3),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                        // AddressInformention::make(), 
                        ...AddressInformention::make("Address info"),   

                    ])
                    ->columns(2),
            ]);
    }
}
