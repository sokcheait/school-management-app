<?php

namespace Modules\Student\Filament\Clusters\Academic\Resources\Teachers\Tables;

use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Modules\Student\Filament\Clusters\Academic\Resources\Teachers\Components\CreateTimeSloteAction;
use Modules\Student\Filament\Clusters\Academic\Resources\Teachers\Components\LogAttendanceAction;
use Modules\Student\Models\TeacherAttendance;
use Modules\Student\Models\TeacherTimeSlot;

class TeacherTable
{
    private static string $sessionKey = 'teacher_layout_view';

    public static function configure(Table $table): Table
    {
        $layoutView = Session::get(self::$sessionKey, 'grid');

        return $table
           ->columns(
                match ($layoutView) {
                    'grid'  => self::gridColumns(),
                    'list'  => self::listColumns(),
                    default => self::tableColumns(),
                }
            )
            ->contentGrid(
                match ($layoutView) {
                    'grid' => ['sm' => 1, 'md' => 2],
                    'list' => ['sm' => 1, 'md' => 1],
                    default => null,
                }
            )
            ->headerActions([
                // Table view
                Action::make('viewTable')
                    ->label('Table')
                    ->icon('heroicon-o-table-cells')
                    ->color($layoutView === 'table' ? 'primary' : 'gray')
                    ->action(function () {
                        Session::put(self::$sessionKey, 'table');
                    })
                    ->after(fn($livewire) => $livewire->resetTable()),

                // Grid view
                Action::make('viewGrid')
                    ->label('Grid')
                    ->icon('heroicon-o-squares-2x2')
                    ->color($layoutView === 'grid' ? 'primary' : 'gray')
                    ->action(function () {
                        Session::put(self::$sessionKey, 'grid');
                    })
                    ->after(fn($livewire) => $livewire->resetTable()),

                // List view
                Action::make('viewList')
                    ->label('List')
                    ->icon('heroicon-o-list-bullet')
                    ->color($layoutView === 'list' ? 'primary' : 'gray')
                    ->action(function () {
                        Session::put(self::$sessionKey, 'list');
                    })
                    ->after(fn($livewire) => $livewire->resetTable()),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->recordActions([
                // self::logAttendanceAction(),
                LogAttendanceAction::make(),
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    CreateTimeSloteAction::make(),

                    // Action::make('create_time_slote')
                    //     ->label('Create Time Slote')
                    //     ->icon('coolicon-timer-add')
                    //     ->color('primary')
                    //     ->schema([
                    //         TimePicker::make('start_time')
                    //             ->label('Start Time')
                    //             ->seconds(false)
                    //             ->native(false)
                    //             ->displayFormat('H:i')
                    //             ->format('H:i')
                    //             ->default(now()),

                    //         TimePicker::make('end_time')
                    //             ->label('End Time')
                    //             ->seconds(false)
                    //             ->native(false)
                    //             ->displayFormat('H:i')
                    //             ->format('H:i')
                    //             ->default(now()),
                    //         Select::make('day_of_week')
                    //             ->label('Day of Week')
                    //             ->multiple()
                    //             ->options([
                    //                 'Monday'    => 'Monday',
                    //                 'Tuesday'   => 'Tuesday',
                    //                 'Wednesday' => 'Wednesday',
                    //                 'Thursday'  => 'Thursday',
                    //                 'Friday'    => 'Friday',
                    //                 'Saturday'  => 'Saturday',
                    //                 'Sunday'    => 'Sunday',
                    //             ])
                    //             ->required(),        
                    //     ])
                    //     ->action(function (array $data, $record) {

                    //     TeacherTimeSlot::create([
                    //         'teacher_id'  => $record->id,
                    //         'day_of_week' => $data['day_of_week'], // already array, cast saves as JSON
                    //         'start_time'  => $data['start_time'],
                    //         'end_time'    => $data['end_time'],
                    //         'is_active'   => true,
                    //     ]);

                    //     Notification::make()
                    //         ->title('Time slot created for ' . count($data['day_of_week']) . ' day(s)')
                    //         ->success()
                    //         ->send();
                    // }),

                    Action::make('listAttendance')
                        ->label('មើលវត្តមាន')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->color('info')
                        ->modalWidth('5xl')
                        ->modalHeading(fn ($record) => 'វត្តមាន — ' . $record->name_kh)
                        ->modalContent(function ($record): View {
                            $dateFrom = request('date_from_' . $record->id, now()->startOfMonth()->format('Y-m-d'));
                            $dateTo   = request('date_to_' . $record->id, now()->endOfMonth()->format('Y-m-d'));

                            $attendances = TeacherAttendance::where('teacher_id', $record->id)
                                ->whereDate('attendance_date', '>=', $dateFrom)
                                ->whereDate('attendance_date', '<=', $dateTo)
                                ->orderByDesc('attendance_date')
                                ->get();

                            $summary = [
                                'total'       => $attendances->count(),
                                'present'     => $attendances->whereIn('status', ['present'])->count(),
                                'late'        => $attendances->where('status', 'late')->count(),
                                'early_leave' => $attendances->where('status', 'early_leave')->count(),
                                'absent'      => $attendances->where('status', 'absent')->count(),
                            ];

                            return view(
                                'student::filament.teachers.teacher-attendance-list',
                                compact('attendances', 'summary', 'dateFrom', 'dateTo', 'record')
                            );
                        })
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('បិទ'),
                ])
                
            ], position: RecordActionsPosition::BeforeColumns);
    }
    private static function gridColumns(): array
    {
        return [
            Split::make([
                SpatieMediaLibraryImageColumn::make('photo')
                    ->collection('teacher')
                    ->circular()
                    ->size(56)
                    ->grow(false)
                    ->defaultImageUrl(fn() => URL::asset('images/default.png')),

                Stack::make([
                    Tables\Columns\TextColumn::make('name_kh')
                        ->label('Name')
                        ->searchable()
                        ->sortable()
                        ->weight(FontWeight::Bold),

                    Tables\Columns\TextColumn::make('teacher_no')
                        ->label('Teacher No')
                        ->searchable()
                        ->sortable()
                        ->icon('heroicon-m-identification')
                        ->color(Color::Gray),

                    Tables\Columns\TextColumn::make('email')
                        ->searchable()
                        ->icon('heroicon-m-envelope')
                        ->color(Color::Gray),

                    Tables\Columns\TextColumn::make('phone')
                        ->icon('heroicon-m-phone')
                        ->color(Color::Gray),
                ]),

                Stack::make([
                    Tables\Columns\IconColumn::make('is_active')
                        ->boolean()
                        ->alignEnd(),

                    Tables\Columns\TextColumn::make('created_at')
                        ->date()
                        ->alignEnd()
                        ->color(Color::Gray),
                ])
                ->alignEnd()
                ->grow(false),
            ]),
        ];
    }

    private static function tableColumns(): array
    {
        return [
            SpatieMediaLibraryImageColumn::make('photo')
                ->collection('teacher')
                ->size(60)
                ->defaultImageUrl(fn() => URL::asset('images/default.png')),

            Tables\Columns\TextColumn::make('name_kh')
                ->label('Name')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('teacher_no')
                ->label('Teacher No')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('email')
                ->searchable(),

            Tables\Columns\TextColumn::make('phone'),

            Tables\Columns\IconColumn::make('is_active')
                ->boolean(),

            Tables\Columns\TextColumn::make('created_at')
                ->date(),
        ];
    }
    private static function listColumns(): array
    {
        return [
            Split::make([
                SpatieMediaLibraryImageColumn::make('photo')
                    ->collection('teacher')
                    ->circular()
                    ->size(44)
                    ->grow(false)
                    ->defaultImageUrl(fn() => URL::asset('images/default.png')),

                Tables\Columns\TextColumn::make('name_kh')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->grow(false),

                Tables\Columns\TextColumn::make('teacher_no')
                    ->label('Teacher No')
                    ->searchable()
                    ->icon('heroicon-m-identification')
                    ->color(Color::Gray)
                    ->grow(false),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->icon('heroicon-m-envelope')
                    ->color(Color::Gray)
                    ->grow(false),

                Tables\Columns\TextColumn::make('phone')
                    ->icon('heroicon-m-phone')
                    ->color(Color::Gray)
                    ->grow(false),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->alignEnd()
                    ->grow(false),

                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->alignEnd()
                    ->color(Color::Gray)
                    ->grow(false),
            ]),
        ];
    }

    public static function logAttendanceAction(): Action
    {
        return Action::make('logAttendance')
            ->label('កត់វត្តមាន')
            ->icon('heroicon-o-check-circle')
            ->color('primary')
            ->modalWidth('lg')

            ->disabled(function ($record) {

                $today = now()->format('l');

                $timeSlot = TeacherTimeSlot::where('teacher_id', $record->id)
                    ->whereJsonContains('day_of_week', $today)
                    ->where('is_active', true)
                    ->first();

                if (! $timeSlot) return true;

                $attendance = TeacherAttendance::where('teacher_id', $record->id)
                    ->whereDate('attendance_date', today())
                    ->first();

                if ($attendance && $attendance->check_in && $attendance->check_out) return true;

                $now       = now();
                $endTime   = Carbon::today()->setTimeFromTimeString($timeSlot->end_time);   // ✅

                if ($now->gt($endTime) && (! $attendance || ! $attendance->check_in)) {
                    return true;
                }

                return false;
            })

            ->mountUsing(function ($form, $record) {

                $today   = now()->format('l');
                $now     = now();
                $current = $now->format('H:i');

                $timeSlot = TeacherTimeSlot::where('teacher_id', $record->id)
                    ->whereJsonContains('day_of_week', $today)
                    ->where('is_active', true)
                    ->first();

                $attendance = TeacherAttendance::where('teacher_id', $record->id)
                    ->whereDate('attendance_date', today())
                    ->first();

                if (! $timeSlot) {
                    $form->fill([
                        'attendance_date'  => $now->format('Y-m-d'),
                        'status'           => 'absent',
                        'check_in'         => null,
                        'check_in_status'  => 'missing',
                        'check_out'        => null,
                        'check_out_status' => 'missing',
                    ]);
                    return;
                }

                // ✅ Always use today's date + time
                $startTime = Carbon::today()->setTimeFromTimeString($timeSlot->start_time);
                $endTime   = Carbon::today()->setTimeFromTimeString($timeSlot->end_time);

                $alreadyCheckedIn = $attendance && ! is_null($attendance->check_in);
                $isCheckOutPhase  = $alreadyCheckedIn && ! $attendance->check_out;

                if ($isCheckOutPhase) {

                    $isEarlyLeave = $now->lt($endTime); // ✅ now compares correctly

                    $form->fill([
                        'attendance_date'  => $now->format('Y-m-d'),
                        'check_in'         => null,
                        'check_in_status'  => $attendance->check_in_status ?? 'on_time',
                        'check_out'        => $current,
                        'check_out_status' => $isEarlyLeave ? 'early_leave' : 'on_time',
                        'status'           => $isEarlyLeave ? 'early_leave' : $attendance->status,
                    ]);

                } else {

                    $isLate = $now->gt($startTime); // ✅ now compares correctly

                    $form->fill([
                        'attendance_date'  => $now->format('Y-m-d'),
                        'check_in'         => $current,
                        'check_in_status'  => $isLate ? 'late' : 'on_time',
                        'check_out'        => null,
                        'check_out_status' => 'missing',
                        'status'           => $isLate ? 'late' : 'present',
                    ]);
                }
            })

            ->form([

                DatePicker::make('attendance_date')
                    ->label('កាលបរិច្ឆេទ')
                    ->required()
                    ->native(false),

                Select::make('status')
                    ->label('ស្ថានភាព')
                    ->options([
                        'present'     => 'មានវត្តមាន',
                        'absent'      => 'អវត្តមាន',
                        'late'        => 'មកយឺត',
                        'leave'       => 'ច្បាប់',
                        'early_leave' => 'ចេញមុនម៉ោង',
                    ])
                    ->disabled()
                    ->dehydrated(true),

                TimePicker::make('check_in')
                    ->label('ម៉ោងចូល')
                    ->seconds(false)
                    ->native(false)
                    ->displayFormat('H:i')
                    ->format('H:i')
                    ->live()
                    ->dehydrated(true)
                    ->hidden(fn (callable $get) => empty($get('check_in')) && ! empty($get('check_out')))
                    ->afterStateUpdated(function ($state, callable $set, $record) {

                        if (! $state || ! $record) return;

                        $timeSlot = TeacherTimeSlot::where('teacher_id', $record->id)
                            ->whereJsonContains('day_of_week', now()->format('l'))
                            ->where('is_active', true)
                            ->first();

                        if (! $timeSlot) return;

                        // ✅ correct comparison
                        $startTime = Carbon::today()->setTimeFromTimeString($timeSlot->start_time);
                        $isLate    = Carbon::parse($state)->gt($startTime);

                        $set('check_in_status', $isLate ? 'late' : 'on_time');
                        $set('status', $isLate ? 'late' : 'present');
                    }),

                TimePicker::make('check_out')
                    ->label('ម៉ោងចេញ')
                    ->seconds(false)
                    ->native(false)
                    ->displayFormat('H:i')
                    ->format('H:i')
                    ->live()
                    ->dehydrated(true)
                    ->hidden(fn (callable $get) => ! empty($get('check_in')))
                    ->afterStateUpdated(function ($state, callable $set, $record) {

                        if (! $state || ! $record) return;

                        $timeSlot = TeacherTimeSlot::where('teacher_id', $record->id)
                            ->whereJsonContains('day_of_week', now()->format('l'))
                            ->where('is_active', true)
                            ->first();

                        if (! $timeSlot) return;

                        $attendance = TeacherAttendance::where('teacher_id', $record->id)
                            ->whereDate('attendance_date', today())
                            ->first();

                        // ✅ correct comparison
                        $endTime      = Carbon::today()->setTimeFromTimeString($timeSlot->end_time);
                        $checkOut     = Carbon::today()->setTimeFromTimeString($state);
                        $isEarlyLeave = $checkOut->lt($endTime);

                        if ($isEarlyLeave) {
                            $set('check_out_status', 'early_leave');
                            $set('status', 'early_leave');
                        } else {
                            $set('check_out_status', 'on_time');
                            $set('status', $attendance->status ?? 'present');
                        }
                    }),

                Hidden::make('check_in_status'),
                Hidden::make('check_out_status'),
            ])

            ->action(function (array $data, $record) {

                $today = now()->format('l');

                $timeSlot = TeacherTimeSlot::where('teacher_id', $record->id)
                    ->whereJsonContains('day_of_week', $today)
                    ->where('is_active', true)
                    ->first();

                // ✅ CHECK-IN
                if (! empty($data['check_in']) && $timeSlot) {

                    $startTime = Carbon::today()->setTimeFromTimeString($timeSlot->start_time);
                    $isLate    = Carbon::today()->setTimeFromTimeString($data['check_in'])->gt($startTime);

                    $data['check_in_status'] = $isLate ? 'late' : 'on_time';
                    $data['status']          = $isLate ? 'late' : 'present';
                }

                // ✅ CHECK-OUT
                if (! empty($data['check_out']) && $timeSlot) {

                    $endTime      = Carbon::today()->setTimeFromTimeString($timeSlot->end_time);
                    $checkOut     = Carbon::today()->setTimeFromTimeString($data['check_out']);
                    $isEarlyLeave = $checkOut->lt($endTime);

                    if ($isEarlyLeave) {
                        $data['check_out_status'] = 'early_leave';
                        $data['status']           = 'early_leave';
                    } else {
                        $existing = TeacherAttendance::where('teacher_id', $record->id)
                            ->whereDate('attendance_date', $data['attendance_date'])
                            ->first();

                        $data['check_out_status'] = 'on_time';
                        $data['status']           = $existing->status ?? 'present';
                    }
                }

                // ✅ ABSENT
                if (empty($data['check_in']) && empty($data['check_out'])) {
                    $data['status']           = 'absent';
                    $data['check_in_status']  = 'missing';
                    $data['check_out_status'] = 'missing';
                }

                TeacherAttendance::updateOrCreate(
                    [
                        'teacher_id'      => $record->id,
                        'attendance_date' => $data['attendance_date'],
                    ],
                    Arr::only($data, [
                        'status',
                        'check_in',
                        'check_in_status',
                        'check_out',
                        'check_out_status',
                    ])
                );

                Notification::make()
                    ->title('រក្សាទុកជោគជ័យ')
                    ->success()
                    ->send();
            });
    }

}