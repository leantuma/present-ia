<div>
    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ __('dashboard.title') }}</h1>

    <!-- Today's Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">{{ __('dashboard.total_employees') }}</h3>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $todayStats['total_employees'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">{{ __('dashboard.checked_in') }}</h3>
            <p class="text-2xl font-bold text-green-600 mt-2">{{ $todayStats['checked_in'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">{{ __('dashboard.late') }}</h3>
            <p class="text-2xl font-bold text-yellow-600 mt-2">{{ $todayStats['late'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">{{ __('dashboard.absent') }}</h3>
            <p class="text-2xl font-bold text-red-600 mt-2">{{ $todayStats['absent'] ?? 0 }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Alerts -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('dashboard.recent_alerts') }}</h2>
            @if(count($recentAlerts) > 0)
            <div class="space-y-3">
                @foreach($recentAlerts as $alert)
                <div class="p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100" wire:click="markAlertAsRead({{ $alert->id }})">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $alert->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $alert->message }}</p>
                            <span class="inline-block mt-2 px-2 py-1 text-xs rounded
                                @if($alert->severity === 'high') bg-red-100 text-red-800
                                @elseif($alert->severity === 'medium') bg-yellow-100 text-yellow-800
                                @else bg-blue-100 text-blue-800
                                @endif">
                                {{ ucfirst($alert->severity) }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-500">{{ $alert->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500">{{ __('dashboard.no_alerts') }}</p>
            @endif
        </div>

        <!-- Recent Attendances -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('dashboard.recent_attendances') }}</h2>
            @if(count($recentAttendances) > 0)
            <div class="space-y-3">
                @foreach($recentAttendances as $attendance)
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $attendance->user->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $attendance->date->format('M d, Y') }}</p>
                            @if($attendance->check_in_at)
                            <p class="text-xs text-gray-500">In: {{ $attendance->check_in_at->format('H:i') }}</p>
                            @endif
                        </div>
                        <span class="px-2 py-1 text-xs rounded
                            @if($attendance->status === 'late') bg-yellow-100 text-yellow-800
                            @elseif($attendance->status === 'absent') bg-red-100 text-red-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ ucfirst($attendance->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500">{{ __('dashboard.no_recent_attendances') }}</p>
            @endif
        </div>
    </div>

    <!-- Weekly Summary (for admins/supervisors) -->
    @if(isset($weeklySummary) && !empty($weeklySummary))
    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('dashboard.weekly_summary') }}</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <h3 class="text-sm font-medium text-gray-500">{{ __('dashboard.check_ins') }}</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $weeklySummary['overview']['total_check_ins'] ?? 0 }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">{{ __('dashboard.late') }}</h3>
                <p class="text-2xl font-bold text-yellow-600">{{ $weeklySummary['overview']['total_late'] ?? 0 }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">{{ __('dashboard.absent') }}</h3>
                <p class="text-2xl font-bold text-red-600">{{ $weeklySummary['overview']['total_absent'] ?? 0 }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">{{ __('dashboard.hours_worked') }}</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $weeklySummary['overview']['total_hours_worked'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Weekly Calendar (for admins/supervisors) -->
    @if(auth()->user() && (auth()->user()->isAdmin() || auth()->user()->isSupervisor()) && !empty($weeklyCalendar))
    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900">{{ __('dashboard.weekly_calendar') }}</h2>
            <div class="flex items-center space-x-2">
                <button wire:click="previousWeek" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                    ← {{ __('dashboard.previous_week') }}
                </button>
                <button wire:click="goToCurrentWeek" class="px-3 py-1 text-sm bg-primary text-white rounded hover:bg-indigo-700">
                    {{ __('dashboard.current_week') }}
                </button>
                <button wire:click="nextWeek" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                    {{ __('dashboard.next_week') }} →
                </button>
            </div>
        </div>
        
        @if(isset($weeklyCalendar['week_start']) && isset($weeklyCalendar['week_end']))
        <p class="text-sm text-gray-600 mb-4">
            {{ __('dashboard.week') }}: {{ $weeklyCalendar['week_start']->format('d M') }} - {{ $weeklyCalendar['week_end']->format('d M Y') }}
        </p>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">
                            {{ __('common.name') }}
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                            Lun<br>{{ isset($weeklyCalendar['days']['monday']['date']) ? $weeklyCalendar['days']['monday']['date']->format('d/m') : '' }}
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                            Mar<br>{{ isset($weeklyCalendar['days']['tuesday']['date']) ? $weeklyCalendar['days']['tuesday']['date']->format('d/m') : '' }}
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                            Mié<br>{{ isset($weeklyCalendar['days']['wednesday']['date']) ? $weeklyCalendar['days']['wednesday']['date']->format('d/m') : '' }}
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                            Jue<br>{{ isset($weeklyCalendar['days']['thursday']['date']) ? $weeklyCalendar['days']['thursday']['date']->format('d/m') : '' }}
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                            Vie<br>{{ isset($weeklyCalendar['days']['friday']['date']) ? $weeklyCalendar['days']['friday']['date']->format('d/m') : '' }}
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                            Sáb<br>{{ isset($weeklyCalendar['days']['saturday']['date']) ? $weeklyCalendar['days']['saturday']['date']->format('d/m') : '' }}
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[120px]">
                            Dom<br>{{ isset($weeklyCalendar['days']['sunday']['date']) ? $weeklyCalendar['days']['sunday']['date']->format('d/m') : '' }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(isset($weeklyCalendar['days']['monday']['employees']) && count($weeklyCalendar['days']['monday']['employees']) > 0)
                        @php
                            $employees = $weeklyCalendar['days']['monday']['employees'];
                        @endphp
                        @foreach($employees as $employeeData)
                            @php
                                $employeeId = $employeeData['employee_id'];
                            @endphp
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10">
                                    {{ $employeeData['employee_name'] }}
                                </td>
                                @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                    @php
                                        $dayEmployees = $weeklyCalendar['days'][$day]['employees'] ?? [];
                                        $dayData = collect($dayEmployees)->firstWhere('employee_id', $employeeId);
                                    @endphp
                                    <td class="px-2 py-3 text-center text-xs">
                                        @if($dayData)
                                            @if($dayData['status'] === 'on_leave')
                                                <div class="bg-blue-100 text-blue-800 rounded p-2">
                                                    <div class="font-semibold">🟦 {{ __('dashboard.on_leave') }}</div>
                                                </div>
                                            @elseif($dayData['status'] === 'present')
                                                <div class="bg-green-100 text-green-800 rounded p-2">
                                                    <div class="font-semibold">✓ {{ __('dashboard.present') }}</div>
                                                    @if($dayData['check_in'])
                                                        <div class="text-xs mt-1">{{ __('dashboard.entry') }}: {{ $dayData['check_in'] }}</div>
                                                    @endif
                                                    @if($dayData['check_out'])
                                                        <div class="text-xs">{{ __('dashboard.exit') }}: {{ $dayData['check_out'] }}</div>
                                                    @endif
                                                </div>
                                            @elseif($dayData['status'] === 'late')
                                                <div class="bg-yellow-100 text-yellow-800 rounded p-2">
                                                    <div class="font-semibold">🟡 {{ __('dashboard.late') }}</div>
                                                    @if($dayData['check_in'])
                                                        <div class="text-xs mt-1">{{ __('dashboard.entry') }}: {{ $dayData['check_in'] }}</div>
                                                    @endif
                                                    @if($dayData['check_out'])
                                                        <div class="text-xs">{{ __('dashboard.exit') }}: {{ $dayData['check_out'] }}</div>
                                                    @endif
                                                </div>
                                            @elseif($dayData['status'] === 'absent')
                                                <div class="bg-red-100 text-red-800 rounded p-2">
                                                    <div class="font-semibold">✗ {{ __('dashboard.absent') }}</div>
                                                </div>
                                            @else
                                                <div class="bg-gray-100 text-gray-600 rounded p-2">
                                                    <div class="text-xs">Sin registro</div>
                                                </div>
                                            @endif
                                        @else
                                            <div class="bg-gray-100 text-gray-600 rounded p-2">
                                                <div class="text-xs">-</div>
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="px-4 py-3 text-center text-sm text-gray-500">
                                {{ __('dashboard.no_employees') }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Legend -->
        <div class="mt-4 flex flex-wrap gap-4 text-xs">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-green-100 border border-green-300 rounded mr-2"></div>
                <span>{{ __('dashboard.present') }}</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-yellow-100 border border-yellow-300 rounded mr-2"></div>
                <span>{{ __('dashboard.late') }}</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-100 border border-red-300 rounded mr-2"></div>
                <span>{{ __('dashboard.absent') }}</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-blue-100 border border-blue-300 rounded mr-2"></div>
                <span>{{ __('dashboard.on_leave') }}</span>
            </div>
        </div>
    </div>
    @endif
</div>

