<div class="fi-modal-content" style="padding: 16px;">

    {{-- Date range display --}}
    <div style="margin-bottom:16px; font-size:13px; color:#6b7280;">
        កំពុងបង្ហាញ:
        <strong style="color:#111827;">
            {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }}
        </strong>
        —
        <strong style="color:#111827;">
            {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}
        </strong>
        ({{ $summary['total'] }} ថ្ងៃ)
    </div>

    {{-- Summary Cards --}}
    @if ($attendances->isNotEmpty())
        <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:12px; margin-bottom:20px;">
            @php
                $cards = [
                    ['label' => 'សរុប',     'value' => $summary['total'],       'bg' => '#f9fafb', 'border' => '#e5e7eb', 'color' => '#374151'],
                    ['label' => 'វត្តមាន',   'value' => $summary['present'],     'bg' => '#f0fdf4', 'border' => '#bbf7d0', 'color' => '#15803d'],
                    ['label' => 'យឺត',      'value' => $summary['late'],        'bg' => '#fefce8', 'border' => '#fde68a', 'color' => '#a16207'],
                    ['label' => 'ចេញមុន',   'value' => $summary['early_leave'], 'bg' => '#fff7ed', 'border' => '#fed7aa', 'color' => '#c2410c'],
                    ['label' => 'អវត្តមាន', 'value' => $summary['absent'],      'bg' => '#fef2f2', 'border' => '#fecaca', 'color' => '#b91c1c'],
                ];
            @endphp
            @foreach ($cards as $card)
                <div style="background:{{ $card['bg'] }}; border:1px solid {{ $card['border'] }}; border-radius:8px; padding:12px; text-align:center;">
                    <div style="font-size:1.5rem; font-weight:700; color:{{ $card['color'] }};">{{ $card['value'] }}</div>
                    <div style="font-size:0.75rem; color:{{ $card['color'] }}; margin-top:2px;">{{ $card['label'] }}</div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Table --}}
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:14px;">
            <thead>
                <tr style="background:#f9fafb; border-bottom:2px solid #e5e7eb;">
                    <th style="padding:10px 16px; text-align:left; font-weight:600; color:#4b5563;">#</th>
                    <th style="padding:10px 16px; text-align:left; font-weight:600; color:#4b5563; white-space:nowrap;">កាលបរិច្ឆេទ</th>
                    <th style="padding:10px 16px; text-align:left; font-weight:600; color:#4b5563; white-space:nowrap;">ស្ថានភាព</th>
                    <th style="padding:10px 16px; text-align:left; font-weight:600; color:#4b5563; white-space:nowrap;">ម៉ោងចូល</th>
                    <th style="padding:10px 16px; text-align:left; font-weight:600; color:#4b5563; white-space:nowrap;">ស្ថានភាពចូល</th>
                    <th style="padding:10px 16px; text-align:left; font-weight:600; color:#4b5563; white-space:nowrap;">ម៉ោងចេញ</th>
                    <th style="padding:10px 16px; text-align:left; font-weight:600; color:#4b5563; white-space:nowrap;">ស្ថានភាពចេញ</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($attendances as $i => $attendance)
                    <tr style="border-bottom:1px solid #f3f4f6;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">

                        {{-- No --}}
                        <td style="padding:10px 16px; color:#9ca3af; font-size:12px;">{{ $i + 1 }}</td>

                        {{-- Date --}}
                        <td style="padding:10px 16px; color:#111827; white-space:nowrap;">
                            {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d/m/Y') }}
                        </td>

                        {{-- Status --}}
                        <td style="padding:10px 16px;">
                            @php
                                $statusMap = [
                                    'present'     => ['label' => 'មានវត្តមាន', 'bg' => '#dcfce7', 'color' => '#15803d'],
                                    'late'        => ['label' => 'មកយឺត',     'bg' => '#fef9c3', 'color' => '#a16207'],
                                    'absent'      => ['label' => 'អវត្តមាន',  'bg' => '#fee2e2', 'color' => '#b91c1c'],
                                    'early_leave' => ['label' => 'ចេញមុន',    'bg' => '#ffedd5', 'color' => '#c2410c'],
                                    'leave'       => ['label' => 'ច្បាប់',     'bg' => '#dbeafe', 'color' => '#1d4ed8'],
                                ];
                                $s = $statusMap[$attendance->status] ?? ['label' => $attendance->status ?? '—', 'bg' => '#f3f4f6', 'color' => '#6b7280'];
                            @endphp
                            <span style="display:inline-flex; align-items:center; padding:2px 10px; border-radius:9999px; font-size:12px; font-weight:500; background:{{ $s['bg'] }}; color:{{ $s['color'] }};">
                                {{ $s['label'] }}
                            </span>
                        </td>

                        {{-- Check In --}}
                        <td style="padding:10px 16px; color:#111827; white-space:nowrap; font-variant-numeric:tabular-nums;">
                            {{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '—' }}
                        </td>

                        {{-- Check In Status --}}
                        <td style="padding:10px 16px;">
                            @php
                                $checkInStatusMap = [
                                    'on_time' => ['label' => 'ទាន់ម៉ោង', 'bg' => '#dcfce7', 'color' => '#15803d'],
                                    'late'    => ['label' => 'យឺត',      'bg' => '#fef9c3', 'color' => '#a16207'],
                                    'missing' => ['label' => 'អវត្តមាន', 'bg' => '#fee2e2', 'color' => '#b91c1c'],
                                ];
                                $ci = $checkInStatusMap[$attendance->check_in_status] ?? ['label' => '—', 'bg' => '#f3f4f6', 'color' => '#9ca3af'];
                            @endphp
                            <span style="display:inline-flex; align-items:center; padding:2px 10px; border-radius:9999px; font-size:12px; font-weight:500; background:{{ $ci['bg'] }}; color:{{ $ci['color'] }};">
                                {{ $ci['label'] }}
                            </span>
                        </td>

                        {{-- Check Out --}}
                        <td style="padding:10px 16px; color:#111827; white-space:nowrap; font-variant-numeric:tabular-nums;">
                            {{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '—' }}
                        </td>

                        {{-- Check Out Status --}}
                        <td style="padding:10px 16px;">
                            @php
                                $checkOutStatusMap = [
                                    'on_time'     => ['label' => 'ទាន់ម៉ោង', 'bg' => '#dcfce7', 'color' => '#15803d'],
                                    'early_leave' => ['label' => 'ចេញមុន',   'bg' => '#ffedd5', 'color' => '#c2410c'],
                                    'missing'     => ['label' => 'មិនទាន់',  'bg' => '#f3f4f6', 'color' => '#6b7280'],
                                ];
                                $co = $checkOutStatusMap[$attendance->check_out_status] ?? ['label' => '—', 'bg' => '#f3f4f6', 'color' => '#9ca3af'];
                            @endphp
                            <span style="display:inline-flex; align-items:center; padding:2px 10px; border-radius:9999px; font-size:12px; font-weight:500; background:{{ $co['bg'] }}; color:{{ $co['color'] }};">
                                {{ $co['label'] }}
                            </span>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding:48px 16px; text-align:center; color:#9ca3af;">
                            <div style="display:flex; flex-direction:column; align-items:center; gap:8px;">
                                <svg style="width:40px; height:40px;" fill="none" viewBox="0 0 24 24" stroke="#d1d5db">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span>មិនមានទិន្នន័យវត្តមាន</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>