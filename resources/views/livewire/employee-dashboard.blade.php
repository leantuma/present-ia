<div>
    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ __('dashboard.title') }}</h1>

    <!-- Monthly Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">{{ __('dashboard.hours_worked') }}</h3>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $monthlyStats['hours_worked'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ __('dashboard.this_month') }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">{{ __('dashboard.times_late') }}</h3>
            <p class="text-2xl font-bold text-yellow-600 mt-2">{{ $monthlyStats['times_late'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ __('dashboard.this_month') }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">{{ __('dashboard.times_absent') }}</h3>
            <p class="text-2xl font-bold text-red-600 mt-2">{{ $monthlyStats['times_absent'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ __('dashboard.this_month') }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-sm font-medium text-gray-500">{{ __('dashboard.days_worked') }}</h3>
            <p class="text-2xl font-bold text-green-600 mt-2">{{ $monthlyStats['days_worked'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ __('dashboard.this_month') }}</p>
        </div>
    </div>

    <!-- Weekly Calendar -->
    <div class="bg-white rounded-lg shadow p-6">
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
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('dashboard.day') }}
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('dashboard.scheduled_time') }}
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('dashboard.actual_time') }}
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('dashboard.status') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(isset($weeklyCalendar['days']))
                        @php
                            $dayNames = [
                                'monday' => 'Lunes',
                                'tuesday' => 'Martes',
                                'wednesday' => 'Miércoles',
                                'thursday' => 'Jueves',
                                'friday' => 'Viernes',
                                'saturday' => 'Sábado',
                                'sunday' => 'Domingo'
                            ];
                        @endphp
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                            @php
                                $dayData = $weeklyCalendar['days'][$day] ?? null;
                            @endphp
                            @if($dayData)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $dayNames[$day] }}<br>
                                    <span class="text-xs text-gray-500">{{ $dayData['date']->format('d/m/Y') }}</span>
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-600">
                                    @if($dayData['scheduled_start'] && $dayData['scheduled_end'])
                                        <div>{{ $dayData['scheduled_start'] }} - {{ $dayData['scheduled_end'] }}</div>
                                    @else
                                        <div class="text-gray-400">-</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-600">
                                    @if($dayData['actual_check_in'] || $dayData['actual_check_out'])
                                        <div>
                                            @if($dayData['actual_check_in'])
                                                <div>{{ __('dashboard.entry') }}: {{ $dayData['actual_check_in'] }}</div>
                                            @endif
                                            @if($dayData['actual_check_out'])
                                                <div>{{ __('dashboard.exit') }}: {{ $dayData['actual_check_out'] }}</div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-gray-400">-</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($dayData['status'] === 'on_leave')
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                            {{ __('dashboard.on_leave') }}
                                        </span>
                                    @elseif($dayData['status'] === 'present')
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                            {{ __('dashboard.present') }}
                                        </span>
                                    @elseif($dayData['status'] === 'late')
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                            {{ __('dashboard.late') }}
                                        </span>
                                    @elseif($dayData['status'] === 'absent')
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                            {{ __('dashboard.absent') }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">
                                            -
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-center text-sm text-gray-500">
                                {{ __('dashboard.no_data') }}
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
</div>

