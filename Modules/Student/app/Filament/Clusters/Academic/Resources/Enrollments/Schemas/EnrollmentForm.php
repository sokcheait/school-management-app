<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Enrollments\Schemas;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Modules\Student\Models\Enrollment;
use Modules\Student\Models\Student;

class EnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            Section::make('Enrollment Info')
                ->columnSpanFull()
                ->schema([
                    // Forms\Components\Select::make('student_id')
                    //     ->relationship(
                    //         name: 'student',
                    //         titleAttribute: 'name_kh',
                    //         modifyQueryUsing: fn ($query) => $query->limit(20)
                    //     )
                    //     ->searchable()
                    //     ->required(),

                    // Forms\Components\Select::make('school_class_id')
                    //     ->relationship('schoolClass', 'name')
                    //     // ->searchable()
                    //     ->required(),

                    // Select::make('student_id')
                    //     ->label('Student Name')
                    //     ->native(false)
                    //     ->allowHtml()
                    //     ->searchable()
                    //     ->options(fn () => Student::with('media')->pluck('name_kh', 'id')->map(function ($name, $id) {
                    //         $avatar = Student::find($id)->getFirstMediaUrl('students');
                    //         return "<div class='flex items-center gap-3'>
                    //                     <img src=`{{url('$avatar')}}` class='w-6 h-6 rounded-full' />
                    //                     <span>{$name}</span>
                    //                 </div>";
                    //     })),

                    Select::make('student_id')
                        ->label('Student Name')
                        ->native(false)
                        ->allowHtml()
                        ->searchable()
                        ->options(function () {
                            return Student::with('media')->get()->mapWithKeys(function ($student) {
                                // dd($student->getFirstMediaUrl('students'));
                                $avatar = $student->getFirstMediaUrl('students');
                                $avatar = $avatar
                                    ? url($avatar)
                                    : asset('images/default.png');

                                return [
                                    $student->id => "
                                        <div class='flex items-center gap-3'>
                                            <img src='{$avatar}' style='width:32px;height:32px;border-radius:9999px;' />
                                            <span>{$student->name_kh}</span>
                                        </div>
                                    "
                                ];
                            });
                        }),

                    Forms\Components\Select::make('school_class_id')
                        ->label('Class')
                        ->relationship('schoolClass', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                            $exists = \Modules\Student\Models\Enrollment::where('student_id', $get('student_id'))
                                ->where('school_class_id', $state)
                                ->exists();

                            if ($exists) {
                                $set('school_class_id', null);

                                \Filament\Notifications\Notification::make()
                                    ->danger()
                                    ->title('Already Enrolled')
                                    ->body('This student is already in this class.')
                                    ->send();
                            }
                        }),

                    Forms\Components\DatePicker::make('enrolled_at')
                        ->default(now()),
                ])        
        ]);
    }
}